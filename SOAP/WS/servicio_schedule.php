<?php
/**
* @author Pablo Fernández Casado
*
* 21/06/2013
* http://www.islavisual.com
*
* Archivo para definir el servicio de un Web Service creado con Zend Framework
*/

class Events {
    /**
      * @var string
      */

    public $event;
}

class Meeting {
    /** @var Events[] */
    public $meeting;
}

//*************************************

class Schedule {
    /**
      * @property string
      * @return Meeting
      */

    public function setMeeting() {
        $schedulle = new Meeting();

        $event = new Events();
        $event->event   = new SoapVar('<event id="1" where="Príncipe de Vergara 10" time="'.$GLOBALS['time'].'">Zend Conference</event>', XSD_ANYXML);
        $schedulle->meeting[] = $event;

        return $schedulle;
    }
}
?>
