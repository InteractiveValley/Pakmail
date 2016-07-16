<?php

namespace InteractiveValley\BackendBundle\Twig;

class StatusExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'status' => new \Twig_Filter_Method($this, 'statusFilter'),
        );
    }

    public function statusFilter($status)
    {
        switch($status){
            case 1:
                $label = '<span class="label label-warning">En proceso</span>';
                break;
            case 2:
                $label = '<span class="label label-success">Revisada</span>';
                break;
            case 3:
                $label = '<span class="label label-danger">Rechazada</span>';
                break;
        }
        return $label;
    }

    public function getName()
    {
        return "status_extension";
    }
}