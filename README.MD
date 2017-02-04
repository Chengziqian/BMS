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
* pre-version 2.2 :
    1. 完成用户信息(user_info.php)编码，增加更改密码以及其他用户信息功能。
    2. 修复JavaScript登录提示Bug(signin.php)。
    3. 增加管理员添加书籍下拉列表的关联(add_book.php),以及书籍编号的提示。
    4. 优化数据表结构(database.sql)。
* pre-version 2.3:
    1. 增加对添加书籍页面的JavaScript的非空检测和正则检测(add_book.php)。
    2. 完成除上传封面功能外的表单提交PHP编码(add_book.php)。
    3. 修改数据表字段名(BMS_books_index/book_code=>BMS_books_index/book_code_index)(database.sql)。
    4. 修改下拉菜单各项的值以适应数据检索(0-11=>00-11)。
* pre-version 2.4
    1. 增加添加书籍上传封面功能，以及文件格式的检测(add_book.php)。
    2. 完成图书馆页面(library.php),图书信息页面(detail_book.php)的编码。
    3. 添加对用户注册，登录空字符串的后台检测。
    4. 修改导航页面信息。
* pre-version 2.5
    1. 完善数据表结构(database.sql)。
    2. 增加历史记录功能，完成(detail_book.php),(user_history.php)编码。
    3. 增加用户申请还书，管理员管理还书功能(user_books.php),(return_books.php)。
    4. 增加用户登录检测逾期书籍，并提示本数的功能(signin.php)。
* per-version 2.6
    1. 完成书籍历史记录基本功能的php编码(all_history.php)。
    2. 完善管理员还书返回影响的功能(return_books.php)。
    3. 修改一些小bug(management.php),(user_history.php)。
* pre-version 2.7
    1. 完成书籍历史记录全部功能的php编码(all_history.php)。
    2. 增加非管理员身份非法访问防护机制(identity_error.php)。
    3. 增加修改书籍索引功能(edit_index.php)。
    4. 完成单个书目历史记录的php编码(single_history.php)。
    5. 美化UI提升用户体验(library.php),(all_history.php),(user_books.php)。

#### 至此，BMS后端开发结束。
---
###Beta版本
* Beta 1.0发布。
* Beta 1.1
    1. 修复用户还书后借书书目出现问题的重大Bug。
    2. 完善页面布局，修复内容增多而body高度未自适应问题。
    3. 完善页面布局，修改index.html的最小宽度。
* Beta 1.2
    1. 设置最小宽度，初步适应移动端浏览页面不发生变形。
    2. 修复主页(index.html)元素未对齐的缺陷。
    3. 修复同一本书历史记录冲突的Bug。