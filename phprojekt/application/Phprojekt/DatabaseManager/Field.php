<?php
/**
 * Represent a field in an active record and hold additional information from
 * the DatabaseManager
 *
 * @copyright  2007 Mayflower GmbH (http://www.mayflower.de)
 * @license    http://phprojekt.com/license PHProjekt 6 License
 * @version    CVS: $Id:
 * @author     David Soria Parra <soria_parra@mayflower.de>
 * @package    PHProjekt
 * @subpackage Core
 * @link       http://www.phprojekt.com
 * @since      File available since Release 1.0
 */

/**
 * Represent a field in an active record and hold additional information from
 * the DatabaseManager
 *
 * @version    Release: @package_version@
 * @license    http://www.phprojekt.com/license PHProjekt6 License
 * @author     Eduardo Polidor <polidor@mayflower.de>
 * @package    PHProjekt
 * @subpackage Core
 * @link       http://www.phprojekt.com
 * @since      File available since Release 1.0
 */
class Phprojekt_DatabaseManager_Field
{
    protected $_dbManager;
    protected $_metadata = array();
    public $right='';
    public $value;

    /**
     * Initialise a new object
     *
     * @param Phprojekt_DatabaseManager $dbm
     * @param string                    $name
     * @param mixed                     $value
     */
    public function __construct(Phprojekt_DatabaseManager $dbm, $name, $value)
    {
        $this->value = (string) $value;
        $this->_metadata = $dbm->find($name);
        $this->right = $dbm->getModel()->getRights();
    }

    public function __get($name)
    {
        if (isset($this->_metadata->$name)) {
            return $this->_metadata->$name;
        }

        return null;
    }

    public function __toString()
    {
        return $this->value;
    }
}