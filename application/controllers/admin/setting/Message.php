<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Message extends AdminController
{
    private $tabId = 0;
    private $data = [];

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('payment_modes_model');
        //$this->load->model('settings_model');

    }

    /* View all settings */
    public function index()
    {

        $this->tabId = $_GET['tg'];


        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
            {

                if (!isset($this->tabId) || empty($this->tabId)) {
                    $this->tabId = 1;
                }
                $this->data['groups'] = \Database\PDO::fetch('SELECT * FROM `tblMessages__group`')[0];
                $this->data['messages'] = \Database\PDO::fetch('SELECT *, m.id AS messageId FROM `tblMessages` m LEFT JOIN `tblMessages__group` `tmg` ON `tmg`.id=m.groupId WHERE m.groupId=' . $this->tabId)[0];
                break;
            }
            case "POST":
            {


                if (isset($_POST['group']) && $_POST['group'] > 0 && isset($_POST['text']) && !empty($_POST['text'])) {

                    $rowId = $this->insert();
                    echo json_encode([
                        'condition' => 'success',
                        'rowId' => $rowId,
                    ]);
                    die;
                } else {
                    $sapi_type = php_sapi_name();
                    if (substr($sapi_type, 0, 3) == 'cgi')
                        header("Status: 400 Bad Request");
                    else
                        header("HTTP/1.1 400 Bad Request");
                    echo json_encode(['condition' => 'failed']);
                    die;
                }

                break;
            }
            case "PUT":
            {
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT['id']) && $_PUT['id'] > 0 && isset($_PUT['group']) && $_PUT['group'] > 0 && isset($_PUT['text']) && !empty($_PUT['text'])) {

                    $this->update($_PUT);
                    echo json_encode([
                        'condition' => 'success',
                    ]);
                    die;
                } else {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode(['condition' => 'failed']);
                    die;
                }

                break;
            }
        }
        $this->load->view('admin/settings/message', $this->data);
    }

    private function insert()
    {
        $sql = "INSERT INTO `tblMessages` (`groupId`,`text`) VALUES({$_POST['group']},'{$_POST['text']}')";
        \Database\PDO::execute($sql);
    }

    private function update($_PUT)
    {
        $sql = "UPDATE `tblMessages` SET  `groupId`={$_PUT['group']}, `text`='{$_PUT['text']}' WHERE id={$_PUT['id']}";
        \Database\PDO::execute($sql);
    }

}
