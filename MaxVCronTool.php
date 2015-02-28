<?php

if (!class_exists('MaxVCronTool')) {
	
	class MaxVCronTool {
		
		private $load_handle_prefix = 'wp-maxv-cron-tool';
		private $plugin_name = 'WP-Cron Tasks';
				
		public function __construct() {
			
			if (is_admin()) {
				add_action('init', array($this,'loadPluginResources'));
			}
			
		}
		
		public function loadPluginResources() {
			add_action('admin_menu',array($this,'addToolsMenu'));
			$this->loadResources();
		}

		public function addToolsMenu() {
			add_submenu_page( 'tools.php', __($this->plugin_name, $this->load_handle_prefix), __($this->plugin_name, $this->load_handle_prefix ), 'manage_options', $this->load_handle_prefix, array(&$this, 'getCronTasksUI' ));
		}
		
		public function getCronTasksUI() {
			$html = '<h4>'.$this->plugin_name.'</h4>';
				
			$html .= '<table class="table table-striped" style="width:100%;word-break: break-all;">';
				
			$html .= '<tr>';
			$html .= '<th>Next Schedule</th>';
			$html .= '<th>Schedule</th>';
			$html .= '<th>Hook Name</th>';
			$html .= '</tr>';
				
			$cron = _get_cron_array();
			$schedules = wp_get_schedules();
			foreach ( $cron as $timestamp => $cronhooks ) {
				foreach ($cronhooks as $hook => $events) {
					foreach ($events as $event ) {
						$html .= '<tr>';
						$html .= '<td>'.date('Y-m-d G:i:s',$timestamp).'</td>';
						$html .= '<td>';
						if ($event['schedule']) {
							$html .= $schedules[$event['schedule']]['display'];
						} else {
							$html .= 'One-off event';
						}
						$html .= '</td>';
						$html .= "<td>$hook</td>";
						$html .= '</tr>';
		
		
					}
				}
			}
				
			$html .= '</table>';
		
			echo $html;
		}
		
		public function loadResources() {
			if (isset($_GET['page']) && $_GET['page']=='wp-maxv-cron-tool') {				
				$path = plugins_url('', __FILE__);
				wp_enqueue_script('jquery');
					
				wp_enqueue_script($this->load_handle_prefix.'-twitter_bootstrap-js', $path.'/js/bootstrap.min.js');
				wp_enqueue_style($this->load_handle_prefix.'-twitter-bootstrap-css', $path.'/css/bootstrap.min.css');
			}
		
		}
		
	}
	
}

?>