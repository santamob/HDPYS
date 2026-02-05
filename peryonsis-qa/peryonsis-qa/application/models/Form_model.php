<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_model extends CI_Model 
{



    public function get_form_types($data_array = '')
    {
        $this->db->select('*');    
        $this->db->from('form_tipleri');
        
        $this->db->where_in("id", $data_array);
        
        $query = $this->db->get();
        
        return $query->num_rows() > 0 ? $query->result() : null;
        // echo $this->db->last_query();
        // exit();
    }

    public function get_forms_of_pernrs_for_ast_ast($year,$donem,$pernr_list,$ft_id)
    {
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT * FROM form_atamalari fa
        LEFT JOIN formlar f ON fa.ft_id = f.ft_id  AND fa.form_id = f.form_id
        JOIN organizasyon_d org_d ON org_d.pernr = fa.pernr AND org_d.yil = fa.yil AND org_d.donem = fa.donem
        WHERE fa.yil = $year AND fa.donem = $donem AND ust_pernr IN ($imploded_pernr_list) AND fa.ft_id = $ft_id";

      

        $query = $this->db->query($sql);


        return $query->num_rows() > 0 ? $query->result() : null;

    }

    public function get_forms_of_pernrs($year,$donem,$ust_pernr,$ft_id)
    {
        
        $sql = "SELECT * FROM form_atamalari fa
        LEFT JOIN formlar f ON fa.ft_id = f.ft_id  AND fa.form_id = f.form_id
        JOIN organizasyon_d org_d ON org_d.pernr = fa.pernr AND org_d.yil = fa.yil AND org_d.donem = fa.donem
        WHERE fa.yil = $year AND fa.donem = $donem AND ust_pernr = $ust_pernr AND fa.ft_id = $ft_id";


        $query = $this->db->query($sql);


        return $query->num_rows() > 0 ? $query->result() : null;

    }

    public function get_ast_ast_employee_data($year,$donem,$pernr_list)
    {
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT * FROM organizasyon_d WHERE yil = $year AND donem = $donem AND ust_pernr IN ($imploded_pernr_list)";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;

    }


    public function get_hedef_form_point($year,$donem,$pernr_list)
    {
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT pernr,SUM(puan) AS hp FROM degerlendirmeler WHERE yil = $year AND donem = $donem AND ft_id = 1 AND pernr IN ($imploded_pernr_list)  GROUP BY pernr";
        
        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }

    public function get_yetkinlik_form_point($year,$donem,$pernr_list)
    {
        $imploded_pernr_list = implode(",",$pernr_list);
        
        $sql = "SELECT pernr,SUM(puan) AS yp FROM degerlendirmeler WHERE yil = $year AND donem = $donem AND ft_id = 2 AND degerlendiren_ack = 'yon1' AND pernr IN ($imploded_pernr_list) GROUP BY pernr";
        
        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function get_forms_result_of_pernrs($year,$donem,$pernr_list)
    {
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT * FROM kademelendirme_sonuclari WHERE yil = $year AND donem = $donem AND pernr IN ($imploded_pernr_list)";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function get_points_of_potential_form_of_pernr_list($year,$donem,$ft_id,$pernr_list)
    {
        

        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT pernr,COUNT(puan) as total_method,SUM(puan) as potential_point FROM `degerlendirmeler` 
        WHERE yil = $year AND donem = $donem AND ft_id = $ft_id AND pernr IN ($imploded_pernr_list) AND degerlendiren_ack = 'yon1' GROUP BY pernr,form_id";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function get_points_hedef_yetkinlik_form_by_session($year,$donem,$pernr)
    {
        $sql = "SELECT pernr,SUM(puan) as yp FROM `degerlendirmeler` d 

        WHERE d.ft_id = 2 AND d.yil = $year AND  d.donem = $donem AND d.pernr = $pernr AND d.degerlendiren_ack = 'rilen'";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function get_total_hedef_and_yetknlik_by_self_session($year,$donem,$pernr)
    {
        $sql = "SELECT pernr,SUM(puan) as total FROM `degerlendirmeler` 

        WHERE yil = $year AND  donem = $donem AND pernr = $pernr AND (ft_id = 1 OR (ft_id = 2 AND degerlendiren_ack = 'rilen'))";


        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }



    public function get_session_form_of_pernr($year,$donem,$pernr,$ft_id)
    {
        $sql = "SELECT * FROM form_atamalari fa
        LEFT JOIN formlar f ON fa.ft_id = f.ft_id  AND fa.form_id = f.form_id
        JOIN organizasyon_d org_d ON org_d.pernr = fa.pernr AND org_d.yil = fa.yil AND org_d.donem = fa.donem
        WHERE fa.yil = $year AND fa.donem = $donem AND fa.pernr = $pernr AND fa.ft_id = $ft_id";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;

    }

    public function get_session_pernr_is_administrator($year,$donem,$pernr)
    {
        $sql = "SELECT * FROM organizasyon_d 
        
        WHERE yil = $year AND donem = $donem AND pernr = $pernr AND yonetici_kontrol='X'";

        $query = $this->db->query($sql);


        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function hedef_confirmation_process($year,$donem,$ft_id,$degerlendiren,$onaylayan)
    {
        //$sql = "select * from  ";

        return null;
    }


    public function get_confirmation_process($year,$donem,$ft_id,$degerlendiren,$onaylayan)
    {

        $imploded_pernr_list = implode(",",$degerlendiren);
        
        /*$sql = "SELECT * FROM onaylar
        
        WHERE yil = $year AND donem = $donem AND ft_id = $ft_id AND onaylayan = $onaylayan AND degerlendiren in ($imploded_pernr_list)";
*/
    
        $sql = "SELECT * FROM organizasyon_d org
        JOIN onaylar o ON org.pernr = o.degerlendiren AND o.yil = org.yil AND o.donem = org.donem
        WHERE org.yil = $year AND org.donem = $donem  AND ft_id = $ft_id AND o.onaylayan = $onaylayan AND o.degerlendiren IN ($imploded_pernr_list)";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;


    }


    public function get_role_of_kademelendirme($year,$donem,$k_pernr)
    {
        
        $sql = "SELECT * FROM kademelendirme_atamalari WHERE yil = $year AND donem = $donem AND  k_pernr = $k_pernr ORDER BY havuz_id";

        $query = $this->db->query($sql);


        return $query->num_rows() > 0 ? $query->result() : null;

    }


    public function get_role_of_kademelendirme_base_of_pernr($year,$donem,$pernr)
    {
        
        $sql = "SELECT * FROM kademelendirme_atamalari WHERE yil = $year AND donem = $donem AND  pernr = $pernr ORDER BY havuz_id";

        $query = $this->db->query($sql);

        


        return $query->num_rows() > 0 ? $query->result() : null;

    }


    public function is_kademelendirme_start($year,$donem,$hevuz_id)
    {
        $sql = "SELECT * FROM kademelendirme_sonuclari WHERE yil = $year AND donem = $donem AND havuz_id = $hevuz_id AND sk = 0";


        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }

    public function get_yetkinlik_gostergeleri()
    {

        $sql = "
        SELECT * FROM form_atamalari fa
        
        JOIN formlar_detay fd ON fd.form_id = fa.form_id
        
        JOIN formlar f ON f.form_id = fd.form_id
        
        JOIN gostergelerv2 g ON g.g_id = fd.g_id
        
        WHERE fa.yil = 2022 AND fa.donem = 3 AND fa.ft_id = 2 AND fa.pernr = 102668";


        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;

    }


    public function get_total_hedef_yetkinlik_points($year,$donem,$pernr_list)
    {
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT pernr,SUM(puan) AS total FROM degerlendirmeler WHERE yil = $year AND donem = $donem AND ft_id IN (1,2) AND degerlendiren_ack = 'yon1' AND pernr IN ($imploded_pernr_list) GROUP BY pernr";


        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function session_is_manager_data($year, $donem, $pernr)
    {
        $sql = "SELECT * FROM organizasyon_d WHERE yil=$year AND donem=$donem AND pernr=$pernr AND yonetici_kontrol = 'X'";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;

    }


    public function session_user_check_degerlendirmeler($year,$donem,$degerlendiren_pernr)
    {
        
        $sql = "SELECT 
        pernr,ft_id, 
        CASE 
        WHEN COUNT(ft_id) > 0 THEN 1
        
        WHEN COUNT(ft_id) = 0 THEN 0
        
        END AS is_ft_filled
        FROM degerlendirmeler WHERE yil = $year AND donem = $donem AND ft_id IN (1,2,3,4,5) AND degerlendiren_pernr IN ($degerlendiren_pernr) AND degerlendiren_ack = 'yon1' GROUP BY pernr,ft_id";

       

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function session_user_check_all_confirmation_to_deep($year,$donem,$session_user_pernr,$ast_list)
    {
        //100733 Akın Bey;
        $array_session = [ $session_user_pernr ];


        $pernr_list = array_merge($array_session, $ast_list);  // 101726,102148,102699 Akın beyin asltları ile birleştir.

        //100733,101726,102148,102699
        $imploded_pernr_list = implode(",",$pernr_list);

        $sql = "SELECT
        o.yil,
        o.donem,
        org.ust_ad_soyad,
        org.ad_soyad,
        org.ust_pernr,
        org.pernr,
        o.onaylayan,
        o.degerlendiren,
        o.ft_id,
        o.onay_durum,
        o.onay_text
       FROM `onaylar` o
       JOIN organizasyon_d org ON o.onaylayan = org.ust_pernr AND o.degerlendiren = org.pernr AND o.yil = org.yil AND o.donem = org.donem
       WHERE o.yil = $year AND o.donem = $donem AND
       o.degerlendiren IN ( 
       
       SELECT ust_pernr AS onaylayan FROM organizasyon_d WHERE yil=$year AND donem = $donem AND ust_pernr IN (SELECT pernr FROM organizasyon_d WHERE yil=$year AND donem = $donem AND ust_pernr IN ($imploded_pernr_list))
       
       ) ORDER BY o.onay_durum DESC";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function is_kademe_manager_is_confirmed($year, $donem, $onaylayan)
    {
        
        $sql = "SELECT * FROM onaylar
        
        WHERE yil = $year AND donem = $donem AND ft_id in (1,2) AND onaylayan = degerlendiren and onaylayan = $onaylayan";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : [];

    }

    public function get_ust_listesi($year,$donem,$session_pernr)
    {
        $sql = "SELECT * from organizasyon_d WHERE pernr = $session_pernr AND yil = $year AND donem = $donem";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function get_ust_confirmation($year,$donem,$onaylayan,$degerlendiren)
    {
        //$sql = "SELECT * FROM onaylar WHERE yil = $year AND donem = $donem AND ft_id = $ft_id AND onaylayan = $onaylayan AND degerlendiren = $degerlendiren";

        $sql = "SELECT * FROM onaylar WHERE yil = $year AND donem = $donem  AND onaylayan = $onaylayan AND degerlendiren = $degerlendiren";

        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : [];

    }

} 