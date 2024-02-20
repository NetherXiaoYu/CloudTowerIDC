## 注意事项
- 本项目仅供学习使用，**请勿将本项目用于生产环境或任何商业用途**，期间产生的任何损失本项目概不负责。
- 本项目目前处于**停更**状态

## 云塔IDC系统
云塔IDC系统是一个免费，开源的网站程序，专注于IDC财务管理。
![d6csMR.png](https://s1.ax1x.com/2020/08/25/d6csMR.png)

## 环境推荐
- .htaccess支持（若不支持需自行转换规则，否则可能会导致数据泄露）
- PHP8.0版本（官方测试环境,3.0.2版本以上务必为php8.0以上，其他版本则不一定需要跟随）
- MySQL5.6.48（官方测试环境,不一定需要跟随）

## 安装
1. 上传源码至根目录（下载教程：[点击观看](https://www.bilibili.com/video/BV1ZD4y1R73j/)）
2. 导入`install.sql`文件到你的数据库
3. 填写`config.php`文件里的数据库信息
4. 安装完成

## 更新：3.0.3
1. 新增自动安装向导
2. 更新后台可查看Log日志文件
3. 更新后台可查看功能插件列表
4. 更新后台可修改功能插件config.json文件
5. `PluginManager`类新增`getPluginConfig`于`setPluginConfig`功能
6. 修复部分PHP8.0报错提示的内容

## 联系我们
- 邮箱： admin@cloudtower.cc
- QQ： 2119566807
- 官网： https://www.cloudtower.cc/

