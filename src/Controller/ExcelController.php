<?php

namespace App\Controller;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\RolesTable $Roles
 */
class ExcelController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function exportUsers()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->loadModel('Users');
        $users = $this->Users->find('all');
        foreach ($users as $key => $user) {
            $sheet->setCellValueExplicitByColumnAndRow(0, $key + 1, $user->name, 's');
            $sheet->setCellValueExplicitByColumnAndRow(1, $key + 1, $user->email, 's');
            $sheet->setCellValueExplicitByColumnAndRow(2, $key + 1, $user->phone_number, 'n');
        }

        $downloadPath = ROOT.'/downloads/files/';
        $date = date('m-d-Y-h-m-s', time());
        $downloadFile = $downloadPath . 'users' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($downloadFile);

        $response = $this->response->withFile($downloadFile);
        return $response;
    }


    public function importUsers()
    {
        if ($this->request->is('post')) {
            if (!empty($this->request->getData()['file']['name'])) {
                $fileName = $this->request->getData()['file']['name'];
                $uploadPath = ROOT.'/uploads/files/';
                $date = date('m-d-Y-h-m-s', time());
                $inputFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                $uploadFile = $uploadPath .pathinfo($fileName)['filename'] . $date. '.'.$inputFileType;
                $numberUsers = 0;
                if (move_uploaded_file($this->request->getData()['file']['tmp_name'], $uploadFile)) {
                    try {
                        if ($inputFileType == 'tsv') {
                            $reader = IOFactory::createReader('Csv');
                            $reader->setDelimiter("\t");
                            $spreadsheet = $reader->load($uploadFile);
                        } elseif ($inputFileType == 'xlsx'){
                            $reader = IOFactory::createReader('Xlsx');
                            $spreadsheet = $reader->load($uploadFile);
                        } elseif ($inputFileType == 'xls'){
                            $reader = IOFactory::createReader('Xls');
                            $spreadsheet = $reader->load($uploadFile);
                        } elseif ($inputFileType == 'csv' ) {
                            $reader = IOFactory::createReader('Csv');
                            $spreadsheet = $reader->load($uploadFile);
                        } else {
                            $this->Flash->error(__('The file format is not valid'));
                            return;
                        }
                        $worksheet = $spreadsheet->getActiveSheet();
                        $this->loadModel('Users');
                        $this->loadModel('Roles');
                        $customerRole = $this->Roles->findAllByName('customer')->first();
                        foreach ($worksheet->getRowIterator() as $row) {
                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(FALSE);
                            $user = $this->Users->newEntity();
                            $exist = false;
                            foreach ($cellIterator as $cell) {
                                if ($cell->getColumn() == 'A') {
                                    if ($cell->getDataType() == 's') {
                                        $user->name = $cell->getValue();
                                    }
                                } elseif ($cell->getColumn() == 'B') {
                                    if ($cell->getDataType() == 's') {
                                        if ($cell->getValue() != '') {
                                            if (!empty($this->Users->findByEmail($cell->getValue())->toArray())) {
                                                $exist = true;
                                            } else {
                                                $user->email = $cell->getValue();
                                            }
                                        }
                                    }
                                } elseif ($cell->getColumn() == 'C') {
                                    if ($cell->getDataType() == 'n') {
                                        $user->phone_number = $cell->getValue();
                                    }
                                }
                            }
                            if ($user->name == null || $user->email == null) {
                                $exist = true;
                            }
                            $user->password = " ";
                            $user->active = false;
                            $user->require_password = true;
                            $user->roles = [$customerRole];
                            if (!$exist) {
                                if (!$this->Users->save($user)) {
                                    $this->Flash->error(__('The user could not be registered. Please, try again.'));
                                    return;
                                } else {
                                    $numberUsers++;
                                }
                            }
                        }
                        $this->Flash->success(__('The users file were imported successfully, ' . $numberUsers . ' new users were saved'));
                    } catch (Exception $e) {
                        $this->Flash->error(__('Error reading file.'));
                    }
                }
            }
        }
    }

    public function isAuthorized($user)
    {
        $this->loadModel('Roles');
        $roles = $this->Roles->find('roled', [
            'users' => $user
        ]);

        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }
}