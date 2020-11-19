function uncond(bt) {
    var cond = document.getElementsByClassName("cond");
    for (var i = 0; i < cond.length; i++) {
        cond[i].className = "btn btn-info cond";
    }
    $("#uncond").removeClass("btn-info")
    $("#uncond").addClass("btn-primary");
    showAll();
}

function reloadDataPage() {
    var result = "", page_count = 0, data_count = 0;
    var doc = $("#all_data").children();
    $(doc).each(function (index, element) {
        if (!$(element).hasClass("hid")) {
            if (data_count % 10 == 0) {
                if (data_count == 0)
                    result += '<div class="page" id="page' + ((data_count / 10) + 1) + '">';
                else
                    result += '<div class="page hid" id="page' + ((data_count / 10) + 1) + '">';
                page_count++;
            }
            result += element.outerHTML;
            if (data_count % 10 == 9) {
                result += '</div>';
            }
            data_count++;
        }
    });
    result += '</div><div class="col-sm-12" style="text-align:center"> <ul class="pagination"> <li class="page_li"><a onclick="change_page(\'pre\',' + page_count + ')" href="#">Previous</a></li>';
    for (var i = 1; i <= page_count; i++) {
        result += '<li class="page_li"><a onclick="change_page(' + i + ',' + page_count + ')" href="#">' + i + '</a></li>';
    }
    result += '<li class="page_li"><a onclick="change_page(\'next\',' + page_count + ')" href="#">Next</a></li></ul></div>';
    $("#student_form").html(result);
    $(".page_li").eq(1).addClass("active")
    $(".page_li").first().addClass("disabled");
}

//重置篩選
function showAll() {
    $("#all_data div").removeClass("hid");
    reloadDataPage();
}

function cond(bt) {
    var Uncond = document.getElementById("uncond");
    var class_name = (bt.id).replace("btn_", ""); //找出當前按鈕的ID,並把"btn_"刪掉

    Uncond.className = "btn btn-info";
    if (bt.className == "btn btn-info cond") { //判斷是否已按下 
        bt.className = "btn btn-primary cond";

        $("#all_data").children().each(function (index, element) { //找尋所有的row
            if ($(element).hasClass("hid")) //已經隱藏的就不再動作
                return;
            $(element).children("a").hasClass(class_name) ? $(element).removeClass("hid") : $(element).addClass("hid"); //還沒隱藏的把它隱藏，已經隱藏的把他還原
        });
    } else {
        bt.className = "btn btn-info cond";

        $("#all_data").children().each(function (index, element) { //找尋所有的row
            var class2 = $(".btn-primary").attr('id'); //找出目前已按下的按鈕
            console.log(class2)
            if (typeof class2 == 'undefined') { //確認class2是否有值
                class2 = "";
                Uncond.className = "btn btn-primary";
                showAll();
            } else
                class2 = class2.replace("btn_", ""); //有值的話，把"btn_"刪掉
            if (!$(element).hasClass("hid") || !$(element).children("a").hasClass(class2)) //已經隱藏的 和 沒有被上個按鈕按下的 不動作
                return;
            $(element).children("a").hasClass(class_name) ? $(element).addClass("hid") : $(element).removeClass("hid");//還沒隱藏的把它隱藏，已經隱藏的把他還原
        });
    }
    reloadDataPage();
}

function sendsearch() {
    var fromDate = new Date(document.getElementById("from-date").value);
    var toDate = new Date(document.getElementById("to-date").value);

    showAll();

    if (Date.parse(fromDate) && Date.parse(toDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#date").text());
            if (!(fromDate <= date && date <= toDate))
                $(element).addClass("hid");
        })
    } else if (Date.parse(fromDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#date").text());
            if (!(fromDate <= date))
                $(element).addClass("hid");
        })
    } else if (Date.parse(toDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#date").text());
            if (!(date <= toDate))
                $(element).addClass("hid");
        })
    }
    reloadDataPage();
}

function visitsearch() {
    var fromDate = new Date(document.getElementById("from-date").value);
    var toDate = new Date(document.getElementById("to-date").value + " 23:59:59");

    showAll();

    if (Date.parse(fromDate) && Date.parse(toDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#visitDateTime").text());
            if (!(fromDate <= date && date <= toDate))
                $(element).addClass("hid");
        })
    } else if (Date.parse(fromDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#visitDateTime").text());
            if (!(fromDate <= date))
                $(element).addClass("hid");
        })
    } else if (Date.parse(toDate)) {
        $("#all_data").children().each(function (index, element) {
            var date = new Date($(element).children("div").children("ul").children("#visitDateTime").text());
            if (!(date <= toDate))
                $(element).addClass("hid");
        })
    }
    reloadDataPage();
}

function name_id_search() {
    var search = document.getElementById("name_or_id").value;
    var nameORid = document.getElementById("searchitem").value;

    showAll();

    if (nameORid != "") {
        $("#all_data").children().each(function (index, element) {
            if (search == "姓名") {
                var name = $(element).children("a").children(".col-sm-3").children().text();
                if (nameORid != name)
                    $(element).addClass("hid");
            } else if (search == "學號") {
                var id = $(element).children("a").children(".col-sm-6").children().text();
                if (nameORid != id)
                    $(element).addClass("hid");
            }
        })
    }
    reloadDataPage();
}

function addrsearch(id = -1) {
    var addr = "";
    if (id == -1)
        addr = document.getElementById("searchitem").value;
    else {
        var search = document.getElementById("name_or_id").value;
        var nameORid = document.getElementById("searchitem").value;
        if (nameORid == "")
            return;
        if (search == "姓名") {
            $("#all_data").children().each(function (index, element) {
                var name = $(element).children("a").children(".col-sm-3").children().text();
                name = name.replace("姓名:", "");
                if (nameORid == name) {
                    addr = $(element).children("div").children("ul").children().children("#address").text();
                    addr = addr.replace("租屋地址:", "");
                    return false;
                }
            })
        } else if (search == "學號") {
            $("#all_data").children().each(function (index, element) {
                var id = $(element).children("div").children("ul").children(".col-sm-12").children("#studentId").text();
                id = id.replace("學號:", "");
                if (nameORid == id) {
                    addr = $(element).children("div").children("ul").children().children("#address").text();
                    addr = addr.replace("租屋地址:", "");
                    return false;
                }
            })
        }
    }

    showAll();

    if (addr != "") {
        $("#all_data").children().each(function (index, element) {
            var stu_addr = $(element).children("div").children("ul").children().children("#address").text();
            stu_addr = stu_addr.replace("租屋地址:", "");
            if (addr != stu_addr)
                $(element).addClass("hid");
        })
    }
    reloadDataPage();
}

function hostsearch() {
    var host = document.getElementById("searchitem").value;

    showAll();

    if (host != "") {
        $("#all_data").children().each(function (index, element) {
            var stu_host = $(element).children("div").children().children().children().children("#landlordName").text();
            stu_host = stu_host.replace("屋主:", "");
            if (host != stu_host)
                $(element).addClass("hid");
        })
    }
    reloadDataPage();
}

function ChoicePdf(){
    count = 0
    year = $("#title").text().split("年第")[0]
    t = $("#title").text().split("年第")[1].split("學")[0]    
    var post_content = "<input name='formtype' value='" + year + "-" + t + "'>";
    post_content += "<input name='student_id' value='"
    $("#all_data div.list-group #student_Id").each(function (index, element) {     
            if(!$(element).closest(".list-group").hasClass("hid")){
                post_content += $(element).text() + " "
                console.log($(element).text())
                count += 1
            }
    })
    post_content += "'>"
    console.log(post_content)
    if(count != 0){
        form = $("#student_id_form")
        form.append(post_content);
        form.submit()
    }else{
        confirm("你他媽沒人交")
    }

}