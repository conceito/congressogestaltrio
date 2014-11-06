<?php

class Value_table{

    protected $table = array(
        'profissional' => array(
            // linha 1
            array(
                'from' => '2014-04-01',
                'to' => '2014-07-10',
                'value' => 245.00,
                'portion' => array('times' => 5, 'value' => 49.00),
                'type' => 'depósito'
            ),
            // linha 2
            array(
                'from' => '2014-07-11',
                'to' => '2014-09-12',
                'value' => 282.00,
                'portion' => array('times' => 2, 'value' => 141.00),
                'type' => 'depósito'
            ),
            // linha 3
            array(
                'from' => '2014-09-13',
                'to' => '2014-10-30',
                'value' => 320.00,
                'portion' => array('times' => 1, 'value' => 320.00),
                'type' => 'depósito'
            ),
            // linha 4
            array(
                'from' => '2014-10-31',
                'to' => '2014-11-22',
                'value' => 350.00,
                'portion' => array('times' => 1, 'value' => 350.00),
                'type' => 'dinheiro'
            )

        ),
        'profissional_grupo' => array(
            // linha 1
            array(
                'from' => '2014-09-11',
                'to' => '2014-10-17',
                'value' => 282.00,
                'portion' => array('times' => 2, 'value' => 141.00),
                'type' => 'depósito'
            )
        ),
        'especializacao' => array(
            // linha 1
            array(
                'from' => '2014-04-01',
                'to' => '2014-07-10',
                'value' => 205.00,
                'portion' => array('times' => 5, 'value' => 41.00),
                'type' => 'depósito'
            ),
            // linha 2
            array(
                'from' => '2014-07-11',
                'to' => '2014-09-12',
                'value' => 236.00,
                'portion' => array('times' => 2, 'value' => 118.00),
                'type' => 'depósito'
            ),
            // linha 3
            array(
                'from' => '2014-09-13',
                'to' => '2014-10-30',
                'value' => 270.00,
                'portion' => array('times' => 1, 'value' => 270.00),
                'type' => 'depósito'
            ),
            // linha 4
            array(
                'from' => '2014-10-31',
                'to' => '2014-11-22',
                'value' => 300.00,
                'portion' => array('times' => 1, 'value' => 300.00),
                'type' => 'dinheiro'
            )
        ),
        'especializacao_grupo' => array(
	        // linha 1
	        array(
		        'from' => '2014-09-11',
		        'to' => '2014-10-17',
		        'value' => 236.00,
		        'portion' => array('times' => 2, 'value' => 118.00),
		        'type' => 'depósito'
	        )
        ),
        'graduacao' => array(
            // linha 1
            array(
                'from' => '2014-04-01',
                'to' => '2014-07-10',
                'value' => 155.00,
                'portion' => array('times' => 5, 'value' => 31.00),
                'type' => 'depósito'
            ),
            // linha 2
            array(
                'from' => '2014-07-11',
                'to' => '2014-10-17',
                'value' => 180.00,
                'portion' => array('times' => 2, 'value' => 90.00),
                'type' => 'depósito'
            ),
            // linha 3
	        array(
                'from' => '2014-10-18',
                'to' => '2014-10-30',
                'value' => 210.00,
                'portion' => array('times' => 1, 'value' => 210.00),
                'type' => 'depósito'
            ),
            // linha 4
            array(
                'from' => '2014-10-31',
                'to' => '2014-11-22',
                'value' => 250.00,
                'portion' => array('times' => 1, 'value' => 250.00),
                'type' => 'dinheiro'
            )
        )
    );

    protected $type = '';

    protected $today = '';

    function __construct()
    {
        $this->today = date("Y-m-d");

    }

    /**
     * @return string
     */
    public function getToday()
    {
        return $this->today;
    }

    /**
     * @param string $today
     */
    public function setToday($today)
    {
        $this->today = $today;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * filter by user type and date
     *
     * <code>
     * // return
     * 'from' => '2014-04-01',
        'to' => '2014-07-10',
        'value' => 155.00,
        'portion' => array('times' => 5, 'value' => 31.00),
        'type' => 'depósito'
     * </code>
     *
     * @param string $type
     * @return array
     */
    public function getByType($type = '')
    {
        $this->setType($type);
        $date = $this->getToday();
        $tableRow = array();

        foreach($this->table[$this->getType()] as $row)
        {
            if($row['from'] <= $date && $row['to'] >= $date)
            {
                $tableRow = $row;
            }
        }

        return $tableRow;

    }
}