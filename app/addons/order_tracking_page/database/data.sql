REPLACE INTO ?:wk_order_labels (`id`, `position`, `statuses`) VALUES
(1, 10, 'a:1:{i:0;s:1:"3";}'),
(2, 20, 'a:1:{i:0;s:1:"1";}'),
(3, 30, 'a:1:{i:0;s:1:"1";}'),
(4, 40, 'a:1:{i:0;s:2:"19";}'),
(5, 50, 'a:1:{i:0;s:1:"2";}');

REPLACE INTO ?:wk_order_labels_description (`id`, `title`, `description`, `lang_code`) VALUES
(1, 'order is placed', '', 'en'),
(2, 'Need Approval', '', 'en'),
(3, 'product is in process', '', 'en'),
(4, 'shipped', '', 'en'),
(5, 'delivered', '', 'en'),
(1, 'order is placed', '', 'ru'),
(2, 'Need Approval', '', 'ru'),
(3, 'product is in process', '', 'ru'),
(4, 'shipped', '', 'ru'),
(5, 'delivered', '', 'ru'),
(1, 'order is placed', '', 'ar'),
(2, 'Need Approval', '', 'ar'),
(3, 'product is in process', '', 'ar'),
(4, 'shipped', '', 'ar'),
(5, 'delivered', '', 'ar');

REPLACE INTO ?:images (`image_id`, `image_path`, `image_x`, `image_y`) VALUES
(8307, '1-placed.png', 60, 60),
(8308, '1-placed.png', 47, 47),
(8309, '2-approval.png', 60, 60),
(8310, '2-approval.png', 60, 60),
(8311, '3-process.png', 60, 60),
(8312, '3-process.png', 60, 60),
(8313, '4-shipped.png', 60, 60),
(8314, '4-shipped.png', 60, 60),
(8315, '5-delivered.png', 60, 60),
(8316, '5-delivered.png', 60, 60);

REPLACE INTO ?:images_links (`pair_id`, `object_id`, `object_type`, `image_id`, `detailed_id`, `type`, `position`) VALUES 
(6341, 1, 'wk_otp_activated_icon', 8307, 0, 'M', 0),
(6342, 1, 'wk_otp_deactivated_icon', 8308, 0, 'M', 0),
(6343, 2, 'wk_otp_activated_icon', 8309, 0, 'M', 0),
(6344, 2, 'wk_otp_deactivated_icon', 8310, 0, 'M', 0),
(6345, 3, 'wk_otp_activated_icon', 8311, 0, 'M', 0),
(6346, 3, 'wk_otp_deactivated_icon', 8312, 0, 'M', 0),
(6347, 4, 'wk_otp_activated_icon', 8313, 0, 'M', 0),
(6348, 4, 'wk_otp_deactivated_icon', 8314, 0, 'M', 0),
(6349, 5, 'wk_otp_activated_icon', 8315, 0, 'M', 0),
(6350, 5, 'wk_otp_deactivated_icon', 8316, 0, 'M', 0);

