<?php 

namespace Tygh\Api\Entities;

use Tygh\Api\AEntity;
use Tygh\Api\Response;

class Complete extends AEntity
{
    public function index($id = 0, $params = array())
    {
        $vendor_payouts = db_get_array("SELECT * FROM ?:vendor_payouts WHERE order_id = $id");

        return array(
            'status' => Response::STATUS_OK,
            'data' => $vendor_payouts
        );
        
    }

    public function create($params)
    {
        return array(
            'status' => Response::STATUS_CREATED,
            'data' => array()
        );
    }

    public function update($id, $params)
    {
        return array(
            'status' => Response::STATUS_OK,
            'data' => array()
        );
    }

    public function delete($id)
    {
        return array(
            'status' => Response::STATUS_NO_CONTENT,
        );
    }

    public function privileges()
    {
        return array(
            'create' => 'create_things',
            'update' => 'edit_things',
            'delete' => 'delete_things',
            'index'  => true,
        );
    }
}

?>