<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('alert_message'))
{
    function alert_message($message, $type = 'danger')
    {
        if ($type == 'danger')
        {
            $alert_message = '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '.
                                $message.
                            '</div>';
        }
        else if ($type == 'success')
        {
            $alert_message = '<div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> '.
                                $message.
                            '</div>';
        }
        else if ($type == 'info')
        {
            $alert_message = '<div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> '.
                                $message.
                            '</div>';
        }
        else if ($type == 'warning')
        {
            $alert_message = '<div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> '.
                                $message.
                            '</div>';
        }
        
        return $alert_message;
        
    }
    
}

?>
