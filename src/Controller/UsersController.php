<?php

namespace App\Controller;

use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Error\Debugger;
use Cake\Network\Http\Client;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\RolesTable $Roles
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout', 'register']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Register method
     *
     * @return \Cake\Network\Response|null Redirects on successful register, renders view otherwise.
     */
    public function register()
    {
        $this->loadModel('Roles');
        $customerRole = $this->Roles->findAllByName('customer')->first();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->roles = [$customerRole];
            $user->active = false;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been registered'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be registered. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $roles = $this->Roles->find('roled', [
                    'users' => $this->Auth->user()
                ]);
                foreach ($roles as $role) {
                    if ($role->name === 'customer') {
                        $this->redirect(['action' => 'view', $user['id']]);
                    }else{
                        break;
                    }
                }
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
                $user = $this->Auth->identify();
            if ($user) {
                if ($user['active'] == 1) {
                    $this->Auth->setUser($user);
                    $this->loadModel('Roles');
                    $roles = $this->Roles->find('roled', [
                        'users' => $this->Auth->user()
                    ]);
                    foreach ($roles as $role) {
                        if ($role->name === 'customer') {
                            $this->redirect(['action' => 'view', $user['id']]);
                        }
                    }
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error('Your user has not been activated.');
            }
            $this->Flash->error('Your username or password is incorrect.');
        } elseif ($this->Auth->user()) {
            $this->redirect(['action' => 'view', $this->Auth->user()['id']]);
        }
    }

    public function logout()
    {
        $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        $this->loadModel('Roles');
        $roles = $this->Roles->find('roled', [
            'users' => $user
        ]);

        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                return true;
            } elseif ($role->name === 'agent') {
                if (in_array($action, ['index', 'view'])) {
                    return true;
                }
                if (!$this->request->getParam('pass.0')) {
                    return false;
                }
                $id = $this->request->getParam('pass.0');
                if ($action === 'edit' && $user['id'] == $id) {
                    return true;
                }
            } elseif ($role->name === 'customer') {
                if (in_array($action, ['view', 'edit'])) {
                    return true;
                }
            }
        }
        return parent::isAuthorized($user);
    }

}
