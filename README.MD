#BMS
###Version(前端)
* pre-version:
    1. 基本首页/登陆/注册 框架。
* pre-version 1.1 :
    1. 个人主页。
* pre-version 1.2 :
    1. 增加user_index.html背景图 。
    2. 完成library.html编码。
* pre-version 1.3 :
    1. 增加管理员主页(management.html),个人书库(user_books.html),管理员添加书籍(add_book.html)。
* pre-version 1.4 :
    1. 增加借书页面(detail_book.html),管理员还书页面(return_books.html)。
    2. 增加还书申请提示(management.html)。
* pre-version 1.5 :
    1. 重构数据库表(database.sql)。
    2. 增加历史纪录页面(all_history.html),(single_history.html),(user_history.html)。
    3. 增加用户信息页面(user_info.html)。
    4. 更换user_index.html背景图(./pictures/bg2.jpg)。
    5. 移除用户设置选项，新增借书记录选项(user_index.html)。
    6. management.html增加查看历史纪录选项。
    7. 调整诸多页面颜色方案。
    8. 优化html/css代码结构。

#### 至此，BMS前端开发结束。
---
###Version(PHP编码)
* pre-version 2.1 :
    1. 完成注册(register.php),登录(signin.php),个人主页(user_index.php)编码，实现注册、登录和注销功能。
    2. 增加注册(register.php)、登录(signin.php)的表单填写JavaScript的检测，以及注册登录提示。
    3. 增加识别用户身份功能(user_index.php),(./icons/fun2_disabled.png)。
    4. 完善数据表结构(database.sql)，增加识别用户名大小写功能。