<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use BENERin\User;

class UserController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, '\BENERin\User', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $user = User::find($parameters);
        if (count($user) == 0) {
            $this->flash->notice("The search did not find any user");

            $this->dispatcher->forward([
                "controller" => "user",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $user = User::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user->getId();

            $this->tag->setDefault("id", $user->getId());
            $this->tag->setDefault("username", $user->getUsername());
            $this->tag->setDefault("password", $user->getPassword());
            $this->tag->setDefault("firstname", $user->getFirstname());
            $this->tag->setDefault("surname", $user->getSurname());
            $this->tag->setDefault("emailAddress", $user->getEmailaddress());
            $this->tag->setDefault("role", $user->getRole());
            $this->tag->setDefault("validationkey", $user->getValidationkey());
            $this->tag->setDefault("status", $user->getStatus());
            $this->tag->setDefault("createdat", $user->getCreatedat());
            $this->tag->setDefault("updatedat", $user->getUpdatedat());
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user = new User();
        $user->setUsername($this->request->getPost("username"));
        $user->setPassword($this->request->getPost("password"));
        $user->setFirstname($this->request->getPost("firstname"));
        $user->setSurname($this->request->getPost("surname"));
        $user->setEmailAddress($this->request->getPost("emailAddress"));
        $user->setRole($this->request->getPost("role"));
        $user->setValidationkey($this->request->getPost("validationkey"));
        $user->setStatus($this->request->getPost("status"));
        $user->setCreatedat($this->request->getPost("createdat"));
        $user->setUpdatedat($this->request->getPost("updatedat"));
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user was created successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user = User::findFirstByid($id);

        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user->setUsername($this->request->getPost("username"));
        $user->setPassword($this->request->getPost("password"));
        $user->setFirstname($this->request->getPost("firstname"));
        $user->setSurname($this->request->getPost("surname"));
        $user->setEmailAddress($this->request->getPost("emailAddress"));
        $user->setRole($this->request->getPost("role"));
        $user->setValidationkey($this->request->getPost("validationkey"));
        $user->setStatus($this->request->getPost("status"));
        $user->setCreatedat($this->request->getPost("createdat"));
        $user->setUpdatedat($this->request->getPost("updatedat"));
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'edit',
                'params' => [$user->getId()]
            ]);

            return;
        }

        $this->flash->success("user was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = User::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => "index"
        ]);
    }

}
