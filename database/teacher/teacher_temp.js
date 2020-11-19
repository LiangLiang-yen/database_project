

var temp_content = "";
var temp = document.createElement("div");

if (typeof teacher_name == 'undefined') {
    teacher_name = "無名";
}

function choice(bt){
    
    bt.classList.add("gradient");
    bt.style.color = "white"
}

function nochoice(bt){
    bt.style.background="white";
}

function load_temp()
{
    temp_content +=  '<nav class="navbar  navbar-default navbar-fixed-top" style="margin-bottom:50px;border-bottom:1px solid black;" >\
                        <div class="container-fluid">\
                            <div class="navbar-header" >\
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">\
                                <span class="icon-bar"></span>\
                                <span class="icon-bar"></span>\
                                <span class="icon-bar"></span>\
                                </button>\
                                <a class="navbar-brand" href="#" style="font-size:36px;margin-left:15px;" >\
                                    <img src="https://www.nfu.edu.tw/images/logo.png" style="height:30px;">\
                                </a>\
                            </div>\
                            <div class="collapse navbar-collapse" id="myNavbar" style="font-size:16px;">\
                                <ul class="nav navbar-nav">\
                                <li style="margin:5px 15px 0px 15px;font-family:serif" onmouseover="choice(this)" onmouseout=" nochoice(this)" ><a href="ManageTable.php">表單管理</a></li>\
                                <li style="margin:5px 15px 0px 15px;font-family:serif" onmouseover="choice(this)" onmouseout=" nochoice(this)"><a href="adm.php">學生表單搜尋</a></li>\
                                <li style="margin:5px 15px 0px 15px;font-family:serif" onmouseover="choice(this)" onmouseout=" nochoice(this)"><a href="ManageUser.php">使用者管理</a></li>\
                                </ul>\
                            <div>\
                            <ul class="nav navbar-nav navbar-right">\
                                <li ><a href="#" disabled="disabled" style="color: pink;margin:5px 15px 0px 15px;">'+ teacher_name +'老師歡迎使用</a>\
                                </li>\
                                <li class="dropdown" style="margin:5px 15px 0px 15px;"><a href="../logout.php" ><span class="glyphicon glyphicon-user"></span> 登出</a></li>\
                            </ul>\
                        </div>\
                    </nav>'
    temp.innerHTML = temp_content;
    document.getElementsByTagName('body')[0].insertBefore(temp,document.body.childNodes[0])
}
