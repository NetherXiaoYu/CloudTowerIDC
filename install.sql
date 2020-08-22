SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ytidc_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` text NOT NULL,
  `permission` text NOT NULL,
  `lastip` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ytidc_admin` (`id`, `username`, `password`, `permission`, `lastip`, `status`) VALUES
(1, 'admin', '14e1b600b1fd579f47433b88e8d85291', '[\"*\"]', '', 1);

CREATE TABLE `ytidc_template` (
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_config` (
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ytidc_config` (`key`, `value`) VALUES
('cron_date', '2020-08-20'),
('cron_deleteday', '0'),
('cron_stopday', '3'),
('seo_description', '专业的轻量IDC系统'),
('seo_keywords', '云塔IDC系统'),
('seo_subtitle', '专业的轻量IDC系统'),
('seo_title', '云塔IDC系统'),
('template', 'default'),
('template_mobile', 'default');

CREATE TABLE `ytidc_gateway` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `rate` decimal(9,2) NOT NULL,
  `plugin` varchar(256) NOT NULL,
  `configoption` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_group` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `weight` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_order` (
  `orderid` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `action` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_priceset` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `weight` int(11) NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `price` text NOT NULL,
  `default` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `weight` int(11) NOT NULL,
  `period` text NOT NULL,
  `group` int(11) NOT NULL,
  `configoption` text NOT NULL,
  `customoption` text NOT NULL,
  `server` int(11) NOT NULL,
  `hidden` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_server` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `serverip` varchar(256) NOT NULL,
  `serverdomain` varchar(256) NOT NULL,
  `serverdns1` varchar(256) NOT NULL,
  `serverdns2` varchar(256) NOT NULL,
  `serverusername` varchar(256) NOT NULL,
  `serverpassword` varchar(256) NOT NULL,
  `serveraccesshash` text NOT NULL,
  `servercpanel` varchar(256) NOT NULL,
  `serverport` int(11) NOT NULL,
  `plugin` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_service` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `buydate` date NOT NULL,
  `enddate` date NOT NULL,
  `period` text NOT NULL,
  `product` int(11) NOT NULL,
  `customoption` text NOT NULL,
  `configoption` text NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_user` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `priceset` int(11) NOT NULL,
  `lastip` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_workorder` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `service` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_workorder_reply` (
  `id` int(11) NOT NULL,
  `person` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `workorder` int(11) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ytidc_admin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_config`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `ytidc_gateway`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_group`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_priceset`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_server`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_service`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_workorder`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_workorder_reply`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `ytidc_gateway`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_priceset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

ALTER TABLE `ytidc_workorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_workorder_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
