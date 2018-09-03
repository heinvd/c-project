<?php
/**
 * Created by PhpStorm.
 * User: heinvd
 * Date: 2018/09/03
 * Time: 00:01
 */

class MessiObject
{
    private $section;
    private $name;
    private $publication;
    private $contact_nr;
    private $email;
    private $join_date;

    private $t_name;
    private $t_publication;
    private $t_contact_nr;
    private $t_email;
    private $t_join_date;

    public function __construct($cells)
    {
        $this->setSection($cells[0]);
        $this->setname($cells[1]);
        $this->setPublication($cells[2]);
        $this->setContactNr($cells[3]);
        $this->setEmail($cells[4]);
        $this->setJoinDate($cells[7]);
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
        if (strlen($publication) == 0) {
            $this->setTPublication('<span style="color:red;">Not Provided</span>');
        }
    }

    /**
     * @return mixed
     */
    public function getContactNr()
    {
        return $this->contact_nr;
    }

    /**
     * @param mixed $contact_nr
     */
    public function setContactNr($contact_nr)
    {

        if (strlen($contact_nr) == 0 || $contact_nr=='n/a') {
            $this->contact_nr[] = $contact_nr;
            $this->setTContactNr('<span style="color:red;">Not Provided</span>');
            return;
        }

        $nrs = explode("/", $contact_nr);

        foreach ($nrs as $nr) {
            $nr = str_replace(' ', '', $nr);
            $nr = str_replace(' ', '', $nr);
            $nr = str_replace('(0)', '', $nr);
            $nr = str_replace('(', '', $nr);
            $nr = str_replace(')', '', $nr);
            $nr = str_replace('-', '', $nr);


            if ((strlen($nr) == 9)) {
                $nr = '27' . $nr;
            }
            if (strlen($nr) == 10 && substr($nr, 0, 1) == 0) {
                $nr = '27' . substr($nr, 1, 9);
            }

            if (substr($nr, 0, 2) == '00') {
                $nr =  strval(substr($nr, 2, strlen($nr) - 2));
            }

            $this->contact_nr[] = "+" . $nr;
        }


    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        if (strlen($email) == 0 || $email == 'n/a') {
            $this->email[] = $email;
            $this->setTEmail('<span style="color:red;">Not Provided  </span>');
            return;
        }

        //first split email provided to see if it was delimited with , or ;
        $delimiter = ';';
        if (strstr($email, ',')) {
            $delimiter = ",";
        }
        $emails = explode($delimiter, $email);

//        var_dump($emails);

        foreach ($emails as $amail) {
            $mail = str_replace(' ', '', $amail);
            $this->email[] = $mail;
            $domain = explode("@", $mail, 2);

            $msg = '';

            if(checkdnsrr($domain[1])) {
                $msg = '<span style="color:green;">Domain exists.</span>';
            } else {
                $msg = '<span style="color:red;">Domain does not exist.</span>';
        }
            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $this->setTEmail('<span style="color:green;">' . $this->getTEmail() . "{$mail} is valid. {$msg}</span>");
            } else {
                $this->setTEmail('<span style="color:red;">' . $this->getTEmail() . "{$mail} is invalid. {$msg}</span>");
            }
        }

    }

    /**
     * @return mixed
     */
    public function getJoinDate()
    {
        return $this->join_date;
    }

    /**
     * @param mixed $join_date
     */
    public function setJoinDate($join_date)
    {
        if($join_date=="" || $join_date=="n/a") {
            $this->setTJoinDate('<span style="color:red;">Not provided.</span>');
            return;
        }

        //test value received
        if(is_int($join_date) || is_float($join_date)) {
            $val = intval(($join_date - 25569) * 86400);
            $this->join_date = date("Y-m-d", $val);
            $this->setTJoinDate("Integer or Float received.  Converted OK.");
            return;
        }

        if(is_string($join_date)) {
            if(strstr($join_date,"/")) {
                $delimeter = "/";
            } else {
                $delimeter = "-";
            }

            $datestr = explode($delimeter,$join_date);
            if($datestr[1]>12) {
                $join_date = $datestr[2] . "/" . $datestr[0] .  "/" . $datestr[1];
            } else {
                $join_date = $datestr[2] . "/" . $datestr[1] .  "/" . $datestr[0];
            }

            $this->join_date = date("Y-m-d", strtotime($join_date));
            $this->setTJoinDate('<span style="color:green;">String received.  Converted OK.</span>');
            return;
        }

        $this->join_date = $join_date;
        $this->setTJoinDate("Not handled.");

    }

    /**
     * @return mixed
     */
    public function getTName()
    {
        return $this->t_name;
    }

    /**
     * @param mixed $t_name
     */
    public function setTName($t_name)
    {
        $this->t_name = $t_name;
    }

    /**
     * @return mixed
     */
    public function getTPublication()
    {
        return $this->t_publication;
    }

    /**
     * @param mixed $t_publication
     */
    public function setTPublication($t_publication)
    {
        $this->t_publication = $t_publication;
    }

    /**
     * @return mixed
     */
    public function getTContactNr()
    {
        return $this->t_contact_nr;
    }

    /**
     * @param mixed $t_contact_nr
     */
    public function setTContactNr($t_contact_nr)
    {
        $this->t_contact_nr = $t_contact_nr;
    }

    /**
     * @return mixed
     */
    public function getTEmail()
    {
        return $this->t_email;
    }

    /**
     * @param mixed $t_email
     */
    public function setTEmail($t_email)
    {
        $this->t_email = $t_email;
    }

    /**
     * @return mixed
     */
    public function getTJoinDate()
    {
        return $this->t_join_date;
    }

    /**
     * @param mixed $t_join_date
     */
    public function setTJoinDate($t_join_date)
    {
        $this->t_join_date = $t_join_date;
    }


}