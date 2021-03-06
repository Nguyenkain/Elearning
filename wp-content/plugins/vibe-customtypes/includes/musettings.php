<?php

include_once('commissions/wplms_commissions_class.php');
include_once('class.export.php');
include_once('class.import.php');

class lms_settings{

	var $option; 

	function __construct(){
		$this->option = 'lms_settings';
		add_action( 'admin_head-post-new.php', array($this,'check_course_limit' ));
	}

	public function vibe_lms_settings() {
	    $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$this->lms_settings_tabs($tab); 
		$this->get_lms_settings($tab);
	}

	function lms_settings_tabs( $current = 'general' ) {
	    $tabs = apply_filters('wplms_lms_settings_tabs',array( 
	    		'general' => __('General','vibe-customtypes'), 
	    		'set_commission' => __('Set Commissions','vibe-customtypes'), 
	    		'pay_commission' => __('Pay Commissions','vibe-customtypes'), 
	    		'functions' => __('Admin Functions','vibe-customtypes'),
	    		'import-export' => __('Import/Export','vibe-customtypes'),
	    		'touch' => __('Touch Points','vibe-customtypes'),
	    		'emails' => __('Emails','vibe-customtypes'),
	    		));
	    echo '<div id="icon-themes" class="icon32"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=lms-settings&tab=$tab'>$name</a>";

	    }
	    echo '</h2>';
	}

	function get_lms_settings($tab){
		if(isset($_POST['save'])){
			echo $this->lms_save_settings($tab);
		}		
		switch($tab){
			case 'pay_commission':
				$this->lms_commission_payments();
			break; 
			case 'set_commission':
				$this->lms_commission_settings();
			break; 
			case 'commission_history':
				$this->lms_commission_history();
			break; 
			case 'instructor':
				$this->lms_instructor_settings();
			break; 
			case 'functions':
				$this->lms_resolve_adhoc_function();
				$this->lms_functions();
			break; 
			case 'import-export':
				$this->lms_import_export();
			break; 
			case 'touch':
				$this->lms_touch_points();
			break; 
			case 'emails':
				$this->lms_emails();
			break; 
			default:
				$this->lms_general_settings();
			break;
		}
	}

	function lms_save_settings($tab){
		if ( !empty($_POST) && check_admin_referer('vibe_lms_settings','_wpnonce') ){
			$lms_settings=array();

			$lms_settings = get_option($this->option);	

			unset($_POST['_wpnonce']);
			unset($_POST['_wp_http_referer']);
			unset($_POST['save']);
			switch($tab){
				case 'instructor':
					$lms_settings['instructor'] = $_POST;
				break;
				case 'student':
					$lms_settings['student'] = $_POST;
				break;
				case 'functions':
					$this->lms_functions();
				break;
				case 'touch':
					$lms_settings['touch'] = $_POST;
				break;
				case 'emails':
					switch($_GET['sub']){
						case 'activate':
							$option = 'activate';
						break;
						case 'schedule':
							$option = 'schedule';
						break;
						default:
							$option = 'email_settings';
						break;
					}
					
					$lms_settings[$option] = $_POST;
				break;
				default:
					$lms_settings['general'] = $_POST;
				break;
			}
			
			update_option($this->option,$lms_settings);

			echo '<div class="updated"><p>'.__('Settings Saved','vibe-customtypes').'</p></div>';
		}else{
			echo '<div class="error"><p>'.__('Unable to Save settings','vibe-customtypes').'</p></div>';
		}
	}


	function lms_general_settings(){
		echo '<h3>'.__('LMS General Settings','vibe-customtypes').'</h3>';
		
		$settings=array(
		array(
				'label' => __('Student Login redirect','vibe-customtypes'),
				'name' =>'student_login_redirect',
				'type' => 'select',
				'options'=>apply_filters('wplms_student_login_redirect_filters',array(
					'home' => __('Home page','vibe-customtypes'),
					'profile' => __('Profile page','vibe-customtypes'),
					'mycourses'=> __('My Courses page','vibe-customtypes'),
					'dashboard'=> __('Dashboard page','vibe-customtypes'),
					'same' => __('Same page','vibe-customtypes'),
					)),
				'desc' => __('Default is home page','vibe-customtypes')
			),
		array(
				'label' => __('Instructor Login redirect','vibe-customtypes'),
				'name' =>'instructor_login_redirect',
				'type' => 'select',
				'options'=>apply_filters('wplms_instructor_login_redirect_filters',array(
					'home' => __('Home page','vibe-customtypes'),
					'profile' => __('Profile page','vibe-customtypes'),
					'mycourses'=> __('My Courses page','vibe-customtypes'),
					'instructing_courses'=> __('Instructing Courses page','vibe-customtypes'),
					'dashboard'=> __('Dashboard page','vibe-customtypes'),
					'same' => __('Same page','vibe-customtypes'),
					)),
				'desc' => __('Default is home page','vibe-customtypes')
			),
		array(
				'label' => __('Hide Members section in Single Course page','vibe-customtypes'),
				'name' =>'hide_course_members',
				'type' => 'checkbox',
				'desc' => __(' Hides member section in course pages','vibe-customtypes')
			),
		array(
				'label' => __('Show curriculum below Course description','vibe-customtypes'),
				'name' =>'course_curriculum_below_description',
				'type' => 'checkbox',
				'desc' => __('Show curriculum below course description','vibe-customtypes')
			),
		array(
				'label' => __('Course Timeline Accordion style','vibe-customtypes'),
				'name' =>'curriculum_accordion',
				'type' => 'checkbox',
				'desc' => __('Show curriculum accordion style','vibe-customtypes')
			),
		array(
				'label' => __('Show User progress in Course Admin','vibe-customtypes'),
				'name' =>'user_progress_course_admin',
				'type' => 'checkbox',
				'desc' => __('Small progress bar is displayed for every user below her name in course -> admin section','vibe-customtypes')
			),
		array(
				'label' => __('Enable Unit/Quiz Start Date time','vibe-customtypes'),
				'name' =>'unit_quiz_start_datetime',
				'type' => 'checkbox',
				'desc' => __('Units and Quizzes start at a particular date and time','vibe-customtypes')
			),
		array(
				'label' => __('Enable One session per user','vibe-customtypes'),
				'name' => 'one_session_per_user',
				'type' => 'checkbox',
				'desc' => __('A User can login from one unique user id (excludes administrators)','vibe-customtypes')
			),
		array(
				'label' => __('Enable In-Course Quiz','vibe-customtypes'),
				'name' => 'in_course_quiz',
				'type' => 'checkbox',
				'desc' => __('Quizzes open inside course like units','vibe-customtypes')
			),
		array(
				'label' => __('In-Course Quiz questions per page','vibe-customtypes'),
				'name' => 'in_course_quiz_paged',
				'type' => 'number',
				'desc' => __('set number of questions appearing per page in in-course quizzes','vibe-customtypes')
			),
		array(
				'label' => __('Hide Administrators in Instructors','vibe-customtypes'),
				'name' =>'admin_instructor',
				'type' => 'checkbox',
				'desc' => __('Hide Administrator in all instructors page & elsewhere','vibe-customtypes')
			),
		array(
				'label' => __('Enable message to Instructor in Course Page','vibe-customtypes'),
				'name' =>'show_message_instructor',
				'type' => 'checkbox',
				'desc' => __('Enables a Message icon to send message to Instructor','vibe-customtypes')
			),
		array(
	            'label' => __('Enable Course Codes', 'vibe-customtypes'),
	            'name' => 'course_codes',
	            'desc' => __('Student can purchase/access courses by using custom defined codes for courses in course pricing section', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Coming soon courses', 'vibe-customtypes'),
	            'name' => 'course_coming_soon',
	            'desc' => __('Enable coming soon option for courses', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable Section Drip feed', 'vibe-customtypes'),
	            'name' => 'course_drip_section',
	            'desc' => __('Drip feed is applied section wise instead of unit wise.', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable passing score for Quiz', 'vibe-customtypes'),
	            'name' => 'quiz_passing_score',
	            'desc' => __('Student progress to next', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Hide correct answers', 'vibe-customtypes'),
	            'name' => 'quiz_correct_answers',
	            'desc' => __('Correct answers in quizzes are not displayed unless student has finished/submitted the course.', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable negative marking', 'vibe-customtypes'),
	            'name' => 'quiz_negative_marking',
	            'desc' => __('Enables negative marking for questions in quizzes', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Show WooCommerce/Pmpro account in profile', 'vibe-customtypes'),
	            'name' => 'woocommerce_account',
	            'desc' => __('Display WooCommerce account in profile', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable Front end course deletion', 'vibe-customtypes'),
	            'name' => 'wplms_course_delete',
	            'desc' => __('Instructors will be able to delete course and related content from front end', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Disable Auto allocation of Free courses', 'vibe-customtypes'),
	            'name' => 'disable_autofree',
	            'desc' => __('Disables auto allocation of free courses', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Show Assignments in Course Curriculum', 'vibe-customtypes'),
	            'name' => 'wplms_course_assignments',
	            'desc' => __('Assignments will be displayed in Course Curriculum', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable Student menus', 'vibe-customtypes'),
	            'name' => 'enable_student_menus',
	            'desc' => __('Adds New menu locations for Students', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable Instructor menus', 'vibe-customtypes'),
	            'name' => 'enable_instructor_menus',
	            'desc' => __('Adds New menu locations for Instructors', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
	            'label' => __('Enable Course forum privacy', 'vibe-customtypes'),
	            'name' => 'enable_forum_privacy',
	            'desc' => __('Only course students can access course forums', 'vibe-customtypes'),
	            'type' => 'checkbox',
			),
		array(
				'label' => __('Display Submission time in Course/Quiz/Assignment submissions','vibe-customtypes'),
				'name' => 'submission_meta',
				'type' => 'checkbox',
				'desc' => __('Displays time (eg 2 hrs) with manual submissions, * requires activity to be enabled','vibe-customtypes')
			),
		array(
				'label' => __('Default order in course directory','vibe-customtypes'),
				'name' =>'default_order',
				'type' => 'select',
				'options'=>array(
					'date' => __('Recent','vibe-customtypes'),
					'title' => __('Alphabetical','vibe-customtypes'),
					'popular' => __('Number of Students','vibe-customtypes'),
					'rated' => __('Rating','vibe-customtypes'),
					),
				'desc' => __('Default is menu order','vibe-customtypes')
			),
		array(
				'label' => __('Default order in Members directory','vibe-customtypes'),
				'name' =>'members_default_order',
				'type' => 'select',
				'options'=>array(
					'active' => __('Last active','vibe-customtypes'),
					'newest' => __('Newest registered','vibe-customtypes'),
					'alphabetical' => __('Alphabetical','vibe-customtypes'),
					),
				'desc' => __('Default is menu order','vibe-customtypes')
			),
		array(
				'label' => __('Instructor Signup Ninja Forms Form ID','vibe-customtypes'),
				'name' => 'instructor_signup_ninja_form_id',
				'type' => 'number',
				'desc' => __('Set Ninja Form ID for Instructor Signup','vibe-customtypes')
			),
		array(
				'label' => __('Limit Number of Courses per Instructor','vibe-customtypes'),
				'name' =>'course_limit',
				'type' => 'number',
				'desc' => __('( 0 for unlimited course per instructor )','vibe-customtypes')
			),
		array(
			'label' => __('Limit Number of Units Created per Instructor','vibe-customtypes'),
			'name' =>'unit_limit',
			'type' => 'number',
			'desc'=>__(' ( 0 for unlimited )','vibe-customtypes')
			),
		array(
			'label' => __('Limit Number of Quiz Created per Instructor ','vibe-customtypes'),
			'name' =>'quiz_limit',
			'type' => 'number',
			'desc' =>__('(0 for unlimited course per instructor )','vibe-customtypes'),
			),
		);

		$this->lms_settings_generate_form('general',$settings);

	}


	function limit_courses_per_month($monthly_limit){
		if(!$monthly_limit)
			return;
		//Limit posts per month
	    $time_in_days = 30; // 1 means in last day
	    $count = $wpdb->get_var(
	        $wpdb->prepare("
	            SELECT COUNT(*) 
	            FROM $wpdb->posts 
	            WHERE post_status = 'publish' 
	            AND post_type = %s 
	            AND post_author = %s
	            AND post_date >= DATE_SUB(CURDATE(),INTERVAL %s DAY)",
	            'course',
	            get_current_user_id(),
	            $time_in_days
	        )
	    );
	    if ( 0 < $count ) 
	    $count = number_format( $count );

	    if ( $monthly_limit <=$count ) {
	         $errors[] = __('You have reached your monthly post limit','vibe-customtypes');
	    }
	}
	function lms_functions(){
		do_action('wplms_admin_custom_admin_panel');
		echo '<h3>'.__('LMS Admin Functions [ For Ad-Hoc Management]','vibe-customtypes').'</h3>';
		echo '<form method="post"><ul class="lms-settings">';
		echo '<li><label>'.__('Custom Field Value','vibe-customtypes').'</label><input type="text" name="id" placeholder="ID"><input type="text" name="field_name" placeholder="Field Name"><input type="text" name="field_value" placeholder="Field Value"><input type="submit" name="set_field" class="button button-primary" value="Set Field" />';
		echo '<li><label>'.__('Custom Field for Student Value','vibe-customtypes').'</label><input type="text" name="student_id" placeholder="Student ID"><input type="text" name="field_name_student" placeholder="Field Name"><input type="text" name="field_value_student" placeholder="Field Value"><input type="submit" name="set_field_for_student" class="button button-primary" value="Set Field" />';
		echo '<li><label>'.__('Current Time Stamp ','vibe-customtypes').'</label><span>'.time().'</span></li>';
		wp_nonce_field('vibe_admin_adhoc','_vibe_admin_adhoc');
		echo '</ul></form>';
		
	}

	function lms_settings_generate_form($tab,$settings=array()){
		echo '<form method="post">';
		wp_nonce_field('vibe_lms_settings','_wpnonce');   
		echo '<table class="form-table">
				<tbody>';		
		
		$lms_settings=get_option($this->option);

		foreach($settings as $setting ){
			echo '<tr valign="top">';
			switch($setting['type']){
				case 'textarea':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><textarea name="'.$setting['name'].'" style="max-width: 560px; height: 240px;border:1px solid #DDD;">'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:'').'</textarea>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'select':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><select name="'.$setting['name'].'">';
					foreach($setting['options'] as $key=>$option){
						echo '<option value="'.$key.'" '.(isset($lms_settings[$tab][$setting['name']])?selected($key,$lms_settings[$tab][$setting['name']]):'').'>'.$option.'</option>';
					}
					echo '</select>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'checkbox':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="checkbox" name="'.$setting['name'].'" '.(isset($lms_settings[$tab][$setting['name']])?'CHECKED':'').' />';
					echo '<span>'.$setting['desc'].'</span>';
				break;
				case 'number':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="number" name="'.$setting['name'].'" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:'').'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'title':
					echo '<th scope="row" class="titledesc"><h3>'.$setting['label'].'</h3></th>';
					echo '<td class="forminp"><hr /></td>';
				break;
				case 'color':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp forminp-color"><input type="text" name="'.$setting['name'].'" class="colorpicker" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:'').'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'hidden':
					echo '<td><input type="hidden" name="'.$setting['name'].'" value="1"/></td>';
				break;
				case 'touchpoint': 
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><strong>'.__('STUDENT','vibe-customtypes').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[student][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['message']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['message']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[student][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['notification']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['notification']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[student][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['email']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['email']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '</td></tr><tr valign="top">';
					echo '<th scope="row"></th>';
					echo '<td class="forminp"><strong>'.__('INSTRUCTOR','vibe-customtypes').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[instructor][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[instructor][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','vibe-customtypes').'&nbsp; <select name="'.$setting['name'].'[instructor][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('No','vibe-customtypes').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('Yes','vibe-customtypes').'</option>';
					echo '</select>';
					echo '</td>
						<tr><td colspan="3"><hr></td>';
				break;
				default:
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="text" name="'.$setting['name'].'" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:'').'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
			}
			
			echo '</tr>';
		}
		echo '</tbody>
		</table>';
		echo '<input type="submit" name="save" value="'.__('Save Settings','vibe-customtypes').'" class="button button-primary" /></form>';
	}	
	// Functioning ===== of SETTINGS
	function lms_resolve_adhoc_function(){
		if ( !isset($_POST['_vibe_admin_adhoc']) || !wp_verify_nonce($_POST['_vibe_admin_adhoc'],'vibe_admin_adhoc') )
		 return;
		else{
			do_action('wplms_admin_custom_admin_process');
			if(isset($_POST['set_field'])){
				$id=$_POST['id'];
				$field_name=$_POST['field_name'];
				$field_value=$_POST['field_value'];
				if(isset($id)){
					if(update_post_meta($id,$field_name,$field_value))
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Field Value Changed','vibe-customtypes').'</p></div>';
					else
						echo '<div id="moderated" class="error below-h2"><p>'.__('Error Field value not changed','vibe-customtypes').'</p></div>';
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Error Field value not entered','vibe-customtypes').'</p></div>';
				}
			}
			if(isset($_POST['set_field_for_student'])){
				$student_id=$_POST['student_id'];
				$field_name=$_POST['field_name_student'];
				$field_value=$_POST['field_value_student'];
				if(strpos($field_value,'|')){
					$field_value=explode('|',$field_value);
				}

				if(isset($student_id)){
					if(update_user_meta($student_id,$field_name,$field_value))
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Student Value Changed','vibe-customtypes').'</p></div>';
					else
						echo '<div id="moderated" class="error below-h2"><p>'.__('Student value not changed','vibe-customtypes').'</p></div>';
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Student value not entered','vibe-customtypes').'</p></div>';
				}
			}
		}
	}

	function check_course_limit() {

		$lms_settings=get_option('lms_settings');

		if(!isset($lms_settings) || !is_array($lms_settings))
			return;

	    global $userdata;
	    global $post_type;
	    

	    global $wpdb;

	    
	    if(in_array('instructor',$userdata->roles)){
			if( $post_type === 'course' && isset($lms_settings['general']['course_limit']) && $lms_settings['general']['course_limit']) {
				$course_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'course' AND post_author = $userdata->ID" );
				if( $course_count >= $lms_settings['general']['course_limit'] ) { wp_die( "Course Limit Exceeded" ); }
			} elseif( $post_type === 'unit' && isset($lms_settings['general']['unit_limit']) && $lms_settings['general']['unit_limit']) {
				$unit_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'unit' AND post_author = $userdata->ID" );
				if( $unit_count >= $lms_settings['general']['unit_limit'] ) { wp_die( "Unit Limit Exceeded" ); }
			} elseif( $post_type === 'quiz' && isset($lms_settings['general']['quiz_limit']) && $lms_settings['general']['quiz_limit']) {
				$quiz_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'quiz' AND post_author = $userdata->ID" );
				if( $quiz_count >= $lms_settings['general']['quiz_limit'] ) { wp_die( "Quiz Limit Exceeded" ); }
			}
		}
		return;
	}

	function lms_commission_settings(){
		echo '<h3>'.__('Set Instructor Commisions','vibe-customtypes').'</h3>';

		if(isset($_POST['set_commission'])){
			if(update_option('instructor_commissions',$_POST['commission']))
				echo '<div id="moderated" class="updated below-h2"><p>'.__('Instructor Commissions Saved','vibe-customtypes').'</p></div>';
			else
				echo '<div id="moderated" class="error below-h2"><p>'.__('Instructor Commissions not saved, contact Site-Admin !','vibe-customtypes').'</p></div>';
			$commission = $_POST['commission'];
		}else{
			$commission = get_option('instructor_commissions');
		}

		$courses = get_posts('post_type=course&post_status=any&posts_per_page=-1');
		
		echo '<form method="POST"><div class="postbox instructor_info">
						<h3><label>'.__('Course Name','vibe-customtypes').'</label><span>'.__('Instructor','vibe-customtypes').'</span><span>'.__('PERCENTAGE','vibe-customtypes').'</span></h3>
						<div class="inside">
							<ul>';
		foreach($courses as $course){
				$instructors=apply_filters('wplms_course_instructors',$course->post_author,$course->ID);
				$cval=array();
				if(isset($commission) && is_array($commission)){
					
					if(isset($instructors) && is_array($instructors)){
						foreach($instructors as $k=>$instructor){
							$cval[$k] = $commission[$course->ID][$instructor];	
						}
					}else{
							$val = $commission[$course->ID][$course->post_author];	
					}
				}else
			 		$val=0;

			 	if(isset($instructors) && is_array($instructors)){
					foreach($instructors as $k=>$instructor){
						echo '<li><label>'.$course->post_title.'</label><span>'.get_the_author_meta('display_name',$instructor).'</span><span><input type="number" name="commission['.$course->ID.']['.$instructor.']" class="small-text" value="'.$cval[$k].'" /></span></li>';
					}	
				}else	
					echo '<li><label>'.$course->post_title.'</label><span>'.get_the_author_meta('display_name',$course->post_author).'</span><span><input type="number" name="commission['.$course->ID.']['.$course->post_author.']" class="small-text" value="'.$val.'" /></span></li>';
		}

		echo '</ul>
						</div>
					</div>
					<input type="submit" class="button-primary" name="set_commission" value="'.__('Set Commisions','vibe-customtypes').'">
			   </form>';
	}

	function lms_commission_payments(){
		echo '<h3>'.__('Pay Instructor Commisions','vibe-customtypes').'</h3>';

		
		if(isset($_POST['set_time'])){
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
		}
		
		if(isset($_POST['payment_complete'])){
			$post = array();
			$post['post_title'] = sprintf(__('Commission Payments on %s','vibe-customtypes'),date('Y-m-d H:i:s'));
			$post['post_status'] = 'publish';
			$post['post_type'] = 'payments';
			$post_id = wp_insert_post( $post, $wp_error );
			if(isset($post_id) && $post_id){
				update_post_meta($post_id,'vibe_instructor_commissions',$_POST['instructor']);
				update_post_meta($post_id,'vibe_date_from',$_POST['start_date']);
				update_post_meta($post_id,'vibe_date_to',$_POST['end_date']);
				echo '<div id="moderated" class="updated below-h2"><p>'.__(' Commission Payments Saved','vibe-customtypes').'</p></div>';
			}else
				echo '<div id="moderated" class="error below-h2"><p>'.__('Commission payments not saved !','vibe-customtypes').'</p></div>';
		}

		
		echo '<form method="POST" name="payment">';
		$posts = get_posts( array ('post_type'=>'payments', 'orderby' => 'date','order'=>'DESC', 'numberposts' => '1' ) );
		foreach($posts as $post){
			$date=$post->post_date;
			$id=$post->ID;
		}
		if(isset($date))
		echo '<strong>LAST PAYMENT : '.date("G:i | D , M j Y", strtotime($date)).'</strong> <a href="'.get_edit_post_link( $id ).'" class="small_link">'.__('CHECK NOW','vibe-customtypes').'</a><br /><br />';
			
		if(!isset($start_date))
			$start_date =  date('Y-m-d', strtotime( date('Ym', current_time('timestamp') ) . '01' ) );
		if(!isset($end_date))
			$end_date = date('Y-m-d', current_time( 'timestamp' ) );	

		echo '<strong>'.__('SET TIME PERIOD','vibe-customtypes').' :</strong><input type="text" name="start_date" id="from" value="'.$start_date.'" class="date-picker-field">
					 <label for="to">&nbsp;&nbsp; To:</label> 
					<input type="text" name="end_date" id="to" value="'.$end_date.'" class="date-picker-field">
					<input type="submit" class="button" name="set_time" value="Show"></p>';

		if(isset($_POST['set_time'])){	

		

		echo '<div class="postbox instructor_info">
						<h3><label>'.__('Instructor Name','vibe-customtypes').'</label><span>'.__('Commission','vibe-customtypes').' ('.get_woocommerce_currency_symbol().')</span><span>'.__('PAYPAL EMAIL','vibe-customtypes').'</span><span>'.__('Select','vibe-customtypes').'</span><span>'.__('Pay via PayPal','vibe-customtypes').'</span></h3>
						<div class="inside">
							<ul>';

					$order_data = new WPLMS_Commissions;
					$instructor_data=$order_data->instructor_data($start_date,$end_date);

					$instructors = get_users('role=instructor');		
					foreach ($instructors as $instructor) {
						$instructor_email = $instructor->user_email;
						if(function_exists('xprofile_get_field_data')){
							$field= vibe_get_option('instructor_paypal_field');
							if( xprofile_get_field_data( $field, $instructor->ID )){
								 $instructor_email=xprofile_get_field_data( $field, $instructor->ID );
							}
						}

						        echo '<li><label>'. $instructor->user_nicename.'</label>
						        <span><input type="number" id="'.$instructor->user_login.'_amount" name="instructor['.$instructor->ID.'][commission]" class="text" value="'.(isset($instructor_data[$instructor->ID])?$instructor_data[$instructor->ID]:0).'" /></span>
						        <span><input type="text" id="'.$instructor->user_login.'_email" name="instructor['.$instructor->ID.'][email]"  value="' . $instructor_email . '" /></span>
						        <span><input type="checkbox" name="instructor['.$instructor->ID.'][set]" class="checkbox" value="1" /></span>
						        <span>
						        <a id="'.$instructor->username.'_payment" class="button">'.__('Pay via PayPal','vibe-customtypes').'</a>
						        
						        </span></li>';
						    }	
					   echo '</ul>
						</div>
					</div>
					<input type="submit" class="button-primary" name="payment_complete" value="'.__('Mark as Paid','vibe-customtypes').'">
					<script>
			        	jQuery(document).ready(function($){
			        		$("#'.$instructor->username.'_payment").click(function(){
			        			var amount =$("#'.$instructor->user_login.'_amount").val();
			        			var email =$("#'.$instructor->user_login.'_email").val();
			        			$(\'<form name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="\'+email+\'"><input type="hidden" name="currency_code" value="'.get_woocommerce_currency().'"><input type="hidden" name="item_name" value="'.__('Instructor Commission','vibe-customtypes').'"><input type="hidden" name="amount" value="\'+amount+\'"></form>\').appendTo($(this)).submit();
			        		});
			        	});
			        </script>		
			   ';	
		}	  

		echo '</form>'; 			
	}	


	function lms_commission_history(){

	}

	function lms_import_export(){
		$url='';
		$wplms_export= new wplms_export();
		$wplms_import = new wplms_import();
		if(isset($_POST['export'])){
			$url=$wplms_export->generate_report();
		}

		echo '<h3>'.__('Import/Export WPLMS Elements','vibe-customtypes').'</h3>';
		echo '<p>'.__('Download and upload in CSV format. Import/Export WPLMS elements with user statuses: Courses, Quizzes, Units, Assignments, Questions and Events.','vibe-customtypes').'</p>';
		
		echo '<hr/><h3>'.__('EXPORT SETTINGS','vibe-customtypes').'</h3>';
		

		$wplms_export->generate_form($url);

		echo '<hr/>';
		echo '<div style="background:#FFF;display:inline-block;padding:20px 30px 30px; margin:30px 0;border-radius:2px;">';
		if(isset($_POST['import'])){
			if(current_user_can('manage_options'))
				$wplms_import->process_upload();
		}
			$wplms_import->generate_form();
		echo '</div>';	
	}

	function lms_touch_points(){
		echo '<h3>'.__('User Touch Points','vibe-customtypes').'</h3>';
		echo '<p>'.__('Set touch points for Students and Instructors in WPLMS. Connect with Student or Instructor via following touch points','vibe-customtypes').'</p>';

			$settings=array(
				'course_announcement'=>array(
									'label' => __('Annoucements','vibe-customtypes'),
									'name' =>'course_announcement',
									'type' => 'touchpoint',
									'hook' => 'wplms_dashboard_course_announcement',
									'params'=>4,
								),
				'course_news'=>array(
									'label' => __('News','vibe-customtypes'),
									'name' =>'course_news',
									'type' => 'touchpoint',
									'hook' => 'publish_post',
									'params'=>2,
								),
				'course_subscribed'=>array(
									'label' => __('Course Subscribed','vibe-customtypes'),
									'name' =>'course_subscribed',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_subscribed',
									'params'=>3,
								),
				'course_added'=>array(
									'label' => __('User added to Course','vibe-customtypes'),
									'name' =>'course_added',
									'type' => 'touchpoint',
									'hook' => 'bulk_action',
									'params'=>3,
								),
				'course_certificate'=>array(
									'label' => __('Course Certificate','vibe-customtypes'),
									'name' =>'course_certificate',
									'type' => 'touchpoint',
									'hook' => 'wplms_certificate_earned',
									'params'=>3,
								),
				'course_badge'=>array(
									'label' => __('Course Badge','vibe-customtypes'),
									'name' =>'course_badge',
									'type' => 'touchpoint',
									'hook' => 'wplms_badge_earned',
									'params'=>4,
								),
				
				'course_reset'=>array(
									'label' => __('Course Reset by Instructor','vibe-customtypes'),
									'name' =>'course_reset',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_reset',
									'params'=>4,
								),
				'course_retake'=>array(
									'label' => __('Course Retake by User','vibe-customtypes'),
									'name' =>'course_retake',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_retake',
									'params'=>2,
								),
				'course_submit'=>array(
									'label' => __('Course Submit','vibe-customtypes'),
									'name' =>'course_submit',
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_course',
									'params'=>2,
								),
				'course_evaluation'=>array(
									'label' => __('Course Evaluation','vibe-customtypes'),
									'name' =>'course_evaluation',
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_course',
									'params'=>3,
								),
				'course_review'=>array(
									'label' => __('Course Reviews','vibe-customtypes'),
									'name' =>'course_review',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_review',
									'params'=>3,
								),
				'course_unsubscribe'=>array(
									'label' => __('Unsubscribe Course','vibe-customtypes'),
									'name' =>'course_unsubscribe',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_unsubscribe',
									'params'=>3,
								),
				'unit_complete'=>array(
									'label' => __('Unit marked complete by User','vibe-customtypes'),
									'name' =>'unit_complete',
									'type' => 'touchpoint',
									'hook' => 'wplms_unit_complete',
									'params'=>3,
								),
				'unit_comment'=>array(
									'label' => __('Unit comment added by User','vibe-customtypes'),
									'name' =>'course_review',
									'type' => 'touchpoint',
									'hook' => 'wplms_course_unit_comment',
									'params'=>3,
								),

				'quiz_start'=>array(
									'label' => __('Quiz Start by user','vibe-customtypes'),
									'name' =>'quiz_start',
									'type' => 'touchpoint',
									'hook' => 'wplms_quiz_start',
									'params'=>2,
								),
				'quiz_submit'=>array(
									'label' => __('Quiz Submitted by user','vibe-customtypes'),
									'name' =>'quiz_submit',
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_quiz',
									'params'=>3,
								),
				'quiz_reset'=>array(
									'label' => __('Quiz Reset by Instructor','vibe-customtypes'),
									'name' =>'course_reset',
									'type' => 'touchpoint',
									'hook' => 'wplms_quiz_reset',
									'params'=>2,
								),
				'quiz_retake'=>array(
									'label' => __('Quiz Retake by User','vibe-customtypes'),
									'name' =>'course_retake',
									'type' => 'touchpoint',
									'hook' => 'wplms_quiz_retake',
									'params'=>1,
								),
				'quiz_evaluation'=>array(
									'label' => __('Quiz Evaluation','vibe-customtypes'),
									'name' =>'quiz_evaluation',
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_quiz',
									'params'=>4,
								),
				'assignment_start'=>array(
									'label' => __('Assignment Start by user','vibe-customtypes'),
									'name' =>'assignment_start',
									'type' => 'touchpoint',
									'hook' => 'wplms_assignment_start',
									'params'=>2,
								),
				'assignment_submit'=>array(
									'label' => __('Assignment Submitted by user','vibe-customtypes'),
									'name' =>'assignment_submit',
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_quiz',
									'params'=>2,
								),
				'assignment_evaluation'=>array(
									'label' => __('Assignment Evaluation','vibe-customtypes'),
									'name' =>'assignment_evaluation',
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_assignment',
									'params'=>4,
								),
							);
			$this->settings = apply_filters('wplms_touch_points',$settings);
			$this->lms_settings_generate_form('touch',$settings);

	}

	function lms_emails(){
		echo '<h3>'.__('Email Settings','vibe-customtypes').'</h3>';
		echo '<p>'.sprintf(__('Configure email template for Emails, recommended plugin for emails %s','vibe-customtypes'),'https://wordpress.org/plugins/wpmandrill/').'</p>';

		$template_array = apply_filters('wplms_email_template_array',array(
			''=> __('Email Options','vibe-customtypes'),
			'activate'=> __('Account Activation Email','vibe-customtypes'),
			'schedule'=> __('Email Schedule','vibe-customtypes'),
			'template'=> __('Email Template','vibe-customtypes'),
			));
		echo '<ul class="subsubsub">';
		foreach($template_array as $k=>$value){
			echo '<li><a href="?page=lms-settings&tab=emails&sub='.$k.'" '.(($k == $_GET['sub'])?'class="current"':'').'>'.$value.'</a> '.(($k=='template')?'':' &#124; ').' </li>';
		}
		echo '</ul>';
		switch($_GET['sub']){
			case 'activate':
				$this->activation_email();
			break;
			case 'schedule':
				$this->email_schedule();
			break;
			case 'template':
				$this->lms_template();
			break;
			default:
				$this->lms_email_settings();
			break;
		}
	}
	function lms_email_settings(){

			$settings=array(
						array(
							'label' => __('FROM "Name"','vibe-customtypes'),
							'name' =>'from_name',
							'type' => 'text',
							'desc' => __('From Name in emails','vibe-customtypes')
						),
						array(
							'label' => __('FROM "Email"','vibe-customtypes'),
							'name' =>'from_email',
							'type' => 'text',
							'desc' => __('From Email in emails','vibe-customtypes')
						),
						array(
							'label' => __('Charset','vibe-customtypes'),
							'name' =>'charset',
							'type' => 'select',
							'options'=>array(
								'utf8' => __('UTF','vibe-customtypes'),
								'iso-8859-1' => __('ISO','vibe-customtypes'),
								),
							'desc' => __('Set email charset','vibe-customtypes')
						),
				);
			$this->settings = apply_filters('wplms_email_settings',$settings);
			$this->lms_settings_generate_form('email_settings',$settings);
	}
	function activation_email(){
		$settings=array(
						array(
							'label' => __('Subject','vibe-customtypes'),
							'name' =>'subject',
							'type' => 'text',
							'desc' => __('Subject in Activation email','vibe-customtypes')
						),
						array(
							'label' => __('Message (supports HTML)','vibe-customtypes'),
							'name' =>'message',
							'type' => 'textarea',
							'desc' => __('Activation email message, user {{activationlink}} to add the activation link','vibe-customtypes')
						),
				);
			$this->settings = apply_filters('wplms_activation_mail',$settings);
			$this->lms_settings_generate_form('activate',$settings);
	}
	function email_schedule(){
		$settings=array(
						array(
							'label' => __('Schedule Drip Feed Email','vibe-customtypes'),
							'name' =>'drip_schedule',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Drip Feed Email','vibe-customtypes'),
							'name' =>'drip',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','vibe-customtypes'),
								'yes' => __('Yes','vibe-customtypes'),
								),
							'desc' => __('Email students when the drip feed units are available','vibe-customtypes')
						),
						array(
							'label' => __('Schedule Email','vibe-customtypes'),
							'name' =>'drip_schedule',
							'type' => 'select',
							'options'=>array(
								'24' => __('Before 24 Hours of unit availability','vibe-customtypes'),
								'12' => __('Before 12 Hours of unit availability','vibe-customtypes'),
								'6' => __('Before 6 Hours of unit availability','vibe-customtypes'),
								'1' => __('Before 1 Hours of unit availability','vibe-customtypes'),
								'0' => __('When Unit is available','vibe-customtypes'),
							),
							'desc' => __('Accuracy of email depends upon site traffic and resources.','vibe-customtypes')
						),
						array(
							'label' => __('Drip Feed Mail Subject','vibe-customtypes'),
							'name' =>'drip_subject',
							'type' => 'text',
							'desc' => __('Subject in Activation email','vibe-customtypes')
						),
						array(
							'label' => __('Drip Feed Mail Message (supports HTML)','vibe-customtypes'),
							'name' =>'drip_message',
							'type' => 'textarea',
							'desc' => __('{{unit}} for Unit name, {{course}} for Course name & link, {{user}} for User name','vibe-customtypes')
						),
						array(
							'label' => __('Schedule Course Expiry Email','vibe-customtypes'),
							'name' =>'e_s',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Course Expire Email','vibe-customtypes'),
							'name' =>'expire',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','vibe-customtypes'),
								'yes' => __('Yes','vibe-customtypes'),
								),
							'desc' => __('Email students when the course expires','vibe-customtypes')
						),
						array(
							'label' => __('Schedule Email','vibe-customtypes'),
							'name' =>'expire_schedule',
							'type' => 'select',
							'options'=>array(
								'24' => __('Before 24 Hours of Course expiry','vibe-customtypes'),
								'12' => __('Before 12 Hours of Course expiry','vibe-customtypes'),
								'6' => __('Before 6 Hours of Course expiry','vibe-customtypes'),
								'1' => __('Before 1 Hours of Course expiry','vibe-customtypes'),
								'0' => __('When Course expires','vibe-customtypes'),
							),
							'desc' => __('Accuracy of email depends upon site traffic and resources.','vibe-customtypes')
						),
						array(
							'label' => __('Course Expire Mail Subject','vibe-customtypes'),
							'name' =>'expire_subject',
							'type' => 'text',
							'desc' => __('Subject in Activation email','vibe-customtypes')
						),
						array(
							'label' => __('Course expire Mail Message (supports HTML)','vibe-customtypes'),
							'name' =>'expire_message',
							'type' => 'textarea',
							'desc' => __('{{course}} for Course name & link, {{user}} for User name','vibe-customtypes')
						),
				);
			$this->settings = apply_filters('wplms_email_schedule',$settings);
			

			$this->lms_settings_generate_form('schedule',$this->settings);
	}

	function lms_template(){

		$template = html_entity_decode(get_option('wplms_email_template'));
		if(!isset($template) || !$template){
			$myFile = __DIR__."/email_templates/template.html";
			$fh = fopen($myFile, 'r');
	        $template =fread($fh,filesize($myFile));
	        fclose($fh);
		}

		echo '<div class="template_controls">
		<h4>'.__('Customize Template','vibe-customtypes').'</h4>
		<ul>
			<li><label>'.__('Email Bg','vibe-customtypes').'</label> <input type="text" class="colorpicker" data-css="background" data-ref="#bodyTable,#emailHeader,#emailFooter" value="#e1e1e1" /></li>
			<li><label>'.__('Email Color','vibe-customtypes').'</label> <input type="text" class="colorpicker" data-css="color" data-ref="#emailHeader,#emailFooter" value="#e1e1e1" /></li>
			<li><label>'.__('Title Bg','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailTitle" value="#3498db" /></li>
			<li><label>'.__('Title Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailTitle" value="#3498db" /></li>
			<li><label>'.__('Greetings BG','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailGreetings" value="#3498db" /></li>
			<li><label>'.__('Greetings Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailGreetings" value="#3498db" /></li>
			<li><label>'.__('Message BG','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailMessage" value="#3498db" /></li>
			<li><label>'.__('Message Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailMessage" value="#3498db" /></li>
			<li><label>'.__('Sender BG','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailSender" value="#3498db" /></li>
			<li><label>'.__('Sender Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailSender" value="#3498db" /></li>
			<li><label>'.__('Item BG','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailItem" value="#3498db" /></li>
			<li><label>'.__('Item Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailItem" value="#3498db" /></li>
			<li><label>'.__('Footer BG','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="background" data-ref="#emailFooter" value="#ffffff" /></li>
			<li><label>'.__('Footer Color','vibe-customtypes').'</label><input type="text" class="colorpicker" data-css="color" data-ref="#emailFooter" value="#bbbbbb" /></li>
		</ul>
		<br class="clear" />
		<a id="show_generated" class="button">'.__('View Generated Template','vibe-customtypes').'</a>
		<a id="restore_default" class="button">'.__('Restore Default','vibe-customtypes').'</a>
		<a id="apply_settings" class="button-primary">'.__('Apply Changes','vibe-customtypes').'</a>
		</div>';
		echo '<div class="wplms_email_template">
		<iframe></iframe>
		</div>
		<textarea id="wplms_email_template">
		'.$template.'
		</textarea>';
		wp_nonce_field('email_template','security');
		echo '<style>input.colorpicker+.iris-picker{position:absolute;}.template_controls{width:100%;margin-bottom:20px;display:inline-block;}.template_controls li label{line-height:2;font-weight:600;}.template_controls li{float:left;margin-right:10px;width:17%;border-radius:3px;border: 1px solid #ddd;padding: 6px;}.template_controls li .colorpicker{width:80px;height:28px;display:inline-block;border:1px solid #DDD;  float: right;}
				#wplms_email_template{display:none;}
				.wplms_email_template iframe{width:100%;height:600px;}
				#wplms_email_template,.wplms_email_template{
				    width: 48%;
				    float:left;
				    margin:1%;
				    height:700px;
				    overflow-y:scroll;
				}
				@media only screen and (max-width: 799px)
				#wplms_email_template,.wplms_email_template{
				  width: 100%; margin:1% 0;
				}</style>';		
	}
}

add_action('wp_ajax_lms_restore_email_template','lms_restore_email_template');
add_action('wp_ajax_lms_save_email_template','lms_save_email_template');

function lms_restore_email_template(){
	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'email_template') ){
	     _e('Security check Failed. Contact Administrator.','vibe-customtypes');
	     die();
	  }

	$myFile = __DIR__."/email_templates/template.html";
	$fh = fopen($myFile, 'r');
    $template =fread($fh,filesize($myFile));
    fclose($fh);
	
	update_option('wplms_email_template',$template);
	echo $template;

	die();  
}

function lms_save_email_template(){
	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'email_template') ){
	     _e('Security check Failed. Contact Administrator.','vibe-customtypes');
	     die();
	  }
	 $template =htmlentities(stripslashes($_POST['template']));
	update_option('wplms_email_template',$template);
	echo __('Template saved','vibe-customtypes');
	die();
}
