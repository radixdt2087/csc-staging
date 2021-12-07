<?php 

namespace Tygh\Api\Entities;

use Tygh\Api\AEntity;
use Tygh\Api\Response;

class Things extends AEntity
{
    public function index($id = 0, $params = array())
    {
        return array(
            'status' => Response::STATUS_OK,
            'data' => "Hello World"
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
            'create' => true,
            'update' => true,
            'delete' => true,
            'index'  => true
        );
    }
}