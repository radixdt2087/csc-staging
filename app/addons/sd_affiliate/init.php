<?php
 defined('BOOTSTRAP') or die('Access denied'); fn_register_hooks( 'update_profile', 'place_order', 'edit_place_order', 'get_users', 'change_location', 'get_user_type_description', 'pre_promotion_check_coupon', 'promotion_check_coupon', 'auth_routines', 'fill_auth', 'profile_fields_areas', 'get_order_info', 'delete_user', 'form_cart', 'get_products_pre', 'get_products', 'check_user_type', 'user_need_login', 'change_order_status', 'delete_order', 'get_user_types', 'check_user_type_access_rules_pre', 'get_feedback_data', 'get_predefined_statuses', 'update_cart_by_data_post', 'init_company_data', 'login_user_post', 'update_user_pre', 'update_ga_orders_info', 'send_mail_pre', 'url_pre', 'get_products_before_select', 'url_post', 'user_exist', 'sd_buy_link_generate_link_post', 'usergroup_types_get_map_user_type', 'usergroup_types_get_list', 'define_usergroups', 'finish_payment' ); if (fn_allowed_for('ULTIMATE')) { fn_register_hooks( 'ult_check_store_permission' ); } if (fn_allowed_for('MULTIVENDOR')) { fn_register_hooks( 'update_company' ); } 