<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cunter config.
 *
 * @class 		Note_Shortcode
 * @version		1.0
 * @package		Note/Classes/for shortcode
 * @category	Class
 * @author 		Mandegarweb
 */
/**
* 
*/

class Amir_Note_Shortcode 
{

	function __construct()
	{
	 add_shortcode('Note', array(&$this,'Amir_Note_cilent_show_user_note'));
	 add_shortcode('note-login', array(&$this,'Amir_Note_login_and_register_form'));
	 add_action('wp_enqueue_scripts',array(&$this,'Amir_Note_js_and_css'));
	 add_action('wp_ajax_Amir_Note_save',array(&$this,'Amir_Note_wp_ajax_save_note')); 

	}
	function Amir_Note_validate_mobile($mobile)
	{
	    return preg_match('/^[0-9]+$/', $mobile);
	}
	
	function Amir_Note_login_and_register_form()
	{
      

	if ( ! is_user_logged_in() ) { // Display WordPress login form:
     $error="";

    if (isset($_POST["cn_user"])) {

    	if ( ! isset( $_POST['Amir_Note_nonce'] ) || ! wp_verify_nonce( $_POST['Amir_Note_nonce'], 'Amir_Note_register' ) ) {
		   $error='<b class="bold red" > '.__("Sorry,Something is Wrong ","note").' </b><br/>';
       }else{


		$mobile = ( isset($_POST['umobile'])) ? sanitize_text_field($_POST['umobile']) : '' ;
	   	$email = ( isset($_POST['uemail'])) ? sanitize_email($_POST['uemail']) : '' ;
	    $pass = ( isset($_POST['upass'])) ? sanitize_text_field($_POST['upass']) : '' ;
		$passa = ( isset($_POST['upassa'])) ? sanitize_text_field($_POST['upassa']) : '' ;

		
		if (empty($mobile)) {
			$error='<b class="bold red" > '.__("Mobile number is empty","note").'</b><br/>';
		}
		if ($this->Amir_Note_validate_mobile($mobile)==false) {
		 	$error='<b class="bold red" > '.__("Invalid mobile number! The number of mobile numbers must be numeric","note").' </b><br/>'; 
		 	}
		if (strlen($mobile)!=11) {
		 	$error='<b class="bold red" > '.__("The mobile number must be 11 digits","note").' </b><br/>';  
		 	}
		if (empty($email)) {
			$error='<b class="bold red" > '.__("Email is empty","note").'</b><br/>';
		}
		if (!is_email(esc_attr($email)))
        {
           $error='<b class="bold red" > '.__("Email is not valid","note").'</b><br/>';

        }
		if (empty($pass)) {
			$error='<b class="bold red" > '.__("The password is empty","note").'</b><br/>';
		}
		if ($passa!=$pass) {
			$error='<b class="bold red" > '.__("The entered passwords are not the same","note").'</b><br/>';
		}
		if ( username_exists( 	$mobile )  && empty($error)) 
		{
			$error='<b class="bold red" > '.__("This phone number is already registered","note").'</b><br/>';
		}
		if ( email_exists( $email ) && empty($error)) 
		{
			$error='<b class="bold red" > '.__("This email is already registered","note").' </b><br/>';

		}
		

		if (empty($error)) {

			/* Create New User*/

			$user_id = wp_create_user( $mobile, $pass, $email );
           if( !is_wp_error($user_id) ) 
           {

           	$data["note"]=__("This is a sample note. You can delete or edit it. To access the edit and delete buttons, move the mouse over the text and use the buttons above the note. If you are using a mobile phone or tablet, click on the note to view these buttons.","note");
           	$data["id_user"]= $user_id; 
           	$date=date("Y-m-d H:i:s"); 
			if (function_exists('jdate'))
			{
			 $date=jdate('Y-m-d H:i:s');
			}$data["date"]=$date;
           	global $wpdb;
		  	 $table=$wpdb->prefix."note";
		  	 $wpdb->insert($table,$data);
             }


			/* End   */ 

		 $error='<b class="bold green" >'.__("Sign up successfully. You are now logged in successfully","note").'</b><br/>';

		}
		

	
		}
	}






		echo "<div class='note-user'>";
			echo "<div class='note-login'>";
		    $args = array(
		        'redirect' => get_option("_login_redirect", true), 
		        'form_id' => 'loginform-custom',
		        'label_username' => __( 'Email or mobile number',"note" ),
		        'label_password' => __( 'Password',"note" ),
		        'label_remember' => __( 'Remember me',"note" ),
		        'label_log_in' => __( 'Login' ,"note"),
		        'remember' => true
		    );
		    wp_login_form( $args );
		  
           
		    echo ("</div>");
		    echo ('<div class="note-register">
		        '.$error.'
			    <form method="post" action="">
			        '.wp_nonce_field( "Amir_Note_register",  "Amir_Note_nonce",  false ,  true).'
			        <label>'.__("Email","note").' </label>  
			        <input id="email" type="text" name="uemail" />
			        <label>'.__("Mobile","note").' </label>  
			        <input type="text"  name="umobile" />
			        <label> '.__("Password","note").' </label>
			        <input type="password"  name="upass" />
			        <label>'.__("Password Again","note").' </label> 
			        <input type="password"  name="upassa" />
			         <input type="submit" value="'.__("Register","note").'" name="cn_user" />
		       </form>');
		    echo ("</div>");
		echo ("</div>");

		} 

	}
	function Amir_Note_wp_ajax_save_note()
	{
		$id=0;
		$response= array(); 

		if ( ! isset( $_POST['note_security'] ) || ! wp_verify_nonce( $_POST['note_security'], 'Amir_Note_command' ) ) {
			$response["error"]=__("Sorry,Something is Wrong ","note"); 
	        echo  json_encode($response);
	        exit();
       }


	  if (isset($_POST["command"])  ) 
	  {
         global $wpdb;
	  	 $table=$wpdb->prefix."note";
	  	 $data["note"]=sanitize_text_field(trim($_POST["note"]));
	  	 $id_user= get_current_user_id() ;
	  	 $data["id_user"]=$id_user;
	  	 switch ($_POST["command"]) {
	  	 	case 'new':
	  	 	   $date=date("Y-m-d H:i:s"); 
			 	if (function_exists('jdate'))
			 	{
				  $date=jdate('Y-m-d H:i:s');
			    }$data["date"]=$date;
			    $response["date"]=$date;
			    $wpdb->insert($table,$data);
	  	        $id=$wpdb->insert_id;
	  	 		break;
	  	 	case 'edit':
	  	 	    $item=intval(str_replace("item-","", sanitize_text_field($_POST["item"])));  
	  	 		$wpdb->update($table,$data, array('id' => $item,"id_user"=>$id_user));
	  	        $id=$item;
	  	 		break;
	  	 	case 'done':
	  	 	    $item=intval(str_replace("c","", sanitize_text_field($_POST["item"])));  
	  	 		$wpdb->update($table,array("do"=>intval($_POST["done"])), array('id' => $item,"id_user"=>$id_user));
	  	        $id=$item;
	  	 		break;

	  	 	case 'remove':
	  	 	    $item=intval(str_replace("item-","", sanitize_text_field($_POST["item"])));  
                $wpdb->delete($table,array("id"=>$item));
	  	        $id=$item;
	  	 		break;
	  	 	default:
	  	 		# code...
	  	 		break;
	  	 }
	  	
	  }
     $response["id"]=$id; 
	  echo  json_encode($response);
	  exit();

	}
	function Amir_Note_js_and_css()
	{
	 wp_register_style('note_css', plugins_url('/css/style.css', __FILE__) );
     wp_enqueue_style( 'note_css' );	
     wp_enqueue_script( "note_js", plugin_dir_url( __FILE__ ) . 'js/note.js', array( 'jquery' ) );
     wp_localize_script( 'note_js', 'the_lab_url', array( 'lab_url' => admin_url( 'admin-ajax.php' ) ) ); 	
	}
	function Amir_Note_cilent_show_user_note()
	{
       $GUID= get_page_link(get_the_ID());
       update_option("_login_redirect", $GUID);
		$id_user=get_current_user_id();
		if ($id_user >=1 ) {
			
		$HTMLS='<div class="note" id="notes">';

		$HTMLS.='
		
		<div class="note-item add">
		
			<h3>'.__("New Note","note").'</h3>
			  <textarea 
			    placeholder="'.__("Click Here...","note").'"
			   id="foo" class="autoExpand" cols="11" var rows="7" data-min-rows="3"></textarea> 
			   <div class="buttonHolder">
                    <button id="newnot">'.__("Save","note").' &nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                </div>
            '.wp_nonce_field( "Amir_Note_command",  "Amir_Note_security",  false ,  true).'

			
			</div>';

             global $wpdb;
	         $table=$wpdb->prefix."note";
	         $notes=$wpdb->get_results("SELECT * FROM $table WHERE id_user=$id_user");
	         foreach ($notes as  $value) { 

	         	$checked="";
	         	if ($value->do==1) {
	         		$checked="checked";
	         	}

       
			$HTMLS.='<div class="note-item" id="item-'.$value->id.'">

			 <div class="icon">
			     <i data-id="item-'.$value->id.'" class="fa fa-times right remove-item" ></i>
                 <i data-id="item-'.$value->id.'" class="fa fa-pencil left edit-item" ></i>
			 </div>

			 <p>'.trim($value->note).'</p>
			 
			   <div class="buttonHolder">
                   <div class="can-toggle can-toggle--size-large">
				  <input id="c'.$value->id.'" type="checkbox" '.$checked.'>
				  <label for="c'.$value->id.'">
				    <div class="can-toggle__switch" data-checked="'.__("Done","note").'" data-unchecked="'.__("Not Done","note").'"></div>
				  </label>
				  <span class="date">'.$value->date.'</span>
				</div>
                </div>
			
			</div>
			';
        }
     
         $HTMLS.=' <div id="id01" class="modal" data-text="'.__("Edit Notes","note").'">
			  <div class="modal-content animate" >
			    <div class="imgcontainer">
			      <span  class="boxclose" title="Close Modal"</span>
			    </div>

			    <div class="container-modal">
			     
			    </div>

			    
			  </div>
			</div>';

			 $HTMLS.=' <div id="loader" class="modal" >
			  <div class="modal-content animate" >
			    <div class="imgcontainer">
			      <span  class="boxclose" title="Close Modal"</span>
			    </div>

			    
			     <div class="loader"></div>
			   

			    
			  </div>
			</div>';

            $HTMLS.='</div>';

            return $HTMLS;
        }
	}

}new Amir_Note_Shortcode() ; 



