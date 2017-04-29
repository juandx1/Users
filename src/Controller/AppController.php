<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Routing\Router;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'unauthorizedRedirect' => $this->referer()
        ]);

        $this->loadComponent('AkkaFacebook.Graph', [
            'app_id' => '194393191079227',
            'app_secret' => '2afb0e005aed31c442917cc6a291e427',
            'app_scope' => 'email,name', // https://developers.facebook.com/docs/facebook-login/permissions/v2.4
            'redirect_url' => Router::url(['controller' => 'Users', 'action' => 'login'], TRUE), // This should be enabled by default
            'post_login_redirect' => Router::url(['controller' => 'Users', 'action' => 'fb_login'], TRUE),
            'user_columns' => ['first_name' => 'name', 'username' => 'email', 'password' => 'password'] //not required
        ]);

        $this->Auth->allow(['display']);

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function beforeFilter(Event $event) {
        if ($this->Auth->user()) {
            $this->set('user', $this->Auth->user());
            $this->set('loggedIn', true);

            $this->loadModel('Roles');
            $roles = $this->Roles->find('roled', [
                'users' => $this->Auth->user()
            ]);

            foreach ($roles as $role) {
                if ($role->name === 'admin') {
                    $this->set('role', 'admin');
                } elseif ($role->name === 'agent') {
                    $this->set('role', 'agent ');
                } elseif ($role->name === 'customer') {
                    $this->set('role', 'customer');
                }
            }
        }else{
            $this->set('loggedIn', false);
        }
    }

    public function isAuthorized($user)
    {
        return false;
    }
}
