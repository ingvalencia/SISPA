<?php

$MyDebug = new MYDEBUG();

/**
 * Class MYDEBUG
 */
class MYDEBUG
{
    /**
     * @var
     */
    var $m_msg;

    /**
     * @var
     */
    var $m_debugOn;

    /**
     * MYDEBUG constructor.
     */
    function MYDEBUG()
    {
        $this->I_Init();
        $this->m_time_inicio = $this->DebugMicrotime();
    }

    /**
     *
     */
    function I_Init()
    {
        $this->m_msg = "";
        $this->m_debugOn = 0;
    }

    /**
     * @param $msg
     */
    function DebugMessage($msg)
    {
        if ($this->m_debugOn) {
            $this->m_msg .= $msg . "<br />";
        }
    }

    /**
     * @return int
     */
    function DebugEnabled()
    {
        return ($this->m_debugOn ? 1 : 0);
    }

    /**
     * @param $debugOn
     */
    function SetDebug($debugOn)
    {
        $this->m_debugOn = $debugOn;
        if ($debugOn == 1) {
            ini_set('display_errors', 1);

            ini_set('scream.enabled', 1);

            error_reporting(E_ALL);
        }
    }

    /**
     *
     */
    function Dump()
    {
        if ($this->m_debugOn) {
            print("<div class=\"dbgText\">" . $this->m_msg . "</div>");
            print("<div class=\"dbgText\">" . ($this->DebugMicrotime() - $this->m_time_inicio) . "</div>\n");
        }
    }

    /**
     * @param $arrayName
     * @param $a
     */
    function DumpArray($arrayName, $a)
    {
        foreach ($a as $k => $v) {
            $this->DebugMessage("$arrayName.[$k] = [$v]");
        }
    }

    /**
     * @param $file
     */
    function DebugInclude($file)
    {
        if ($this->m_debugOn) {
            print("<div class=\"dbgInclude\">" . $file . "</div>");
        }
    }

    /**
     * @return float
     */
    function DebugMicrotime()
    {
        list($useg, $seg) = explode(" ", microtime());

        return ((float) $useg + (float) $seg);
    }

    /**
     *
     */
    function DebugError()
    {
        if ($this->m_debugOn) {

            ini_set('display_errors', 1);

            error_reporting(E_ALL);
        }
    }
}

?>
