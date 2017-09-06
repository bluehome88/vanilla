<?php
/**
 * @copyright 2009-2017 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 */

/**
 * Class SessionModel
 */
class SessionModel extends Gdn_Model {
    use \Vanilla\PrunableTrait;

    /**
     * Class constructor. Defines the related database table name.
     */
    public function __construct() {
        parent::__construct('Session');
        $this->setPruneField('DateExpire');
        $this->setPruneAfter('45 minutes');
    }

    /**
     * @inheritdoc
     */
    public function insert($fields) {
        $this->prune();

        if (!isset($fields['DateInserted'])) {
            $fields['DateInserted'] = date(MYSQL_DATE_FORMAT);
        }

        return parent::insert($fields);
    }

    /**
     * @inheritdoc
     */
    public function update($fields, $where = false, $limit = false) {
        if (!isset($fields['DateUpdated'])) {
            $fields['DateUpdated'] = date(MYSQL_DATE_FORMAT);
        }

        parent::update($fields, $where, $limit);
    }
}
