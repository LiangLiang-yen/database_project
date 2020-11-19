/*
  var landlord_name 房東姓名
  var landlord_pnumber  房東電話號碼
  var student_addr 學生租屋地址
  var student_class 學生班級
  var student_ID  學號
  var student_name  學生姓名

  表單問題表格
  var question_table = [{question : "李敬是醜男嗎?", anser : 1},
                        {question : "李敬是醜男嗎?", anser : 1},
                        {question : "李敬是醜男嗎?", anser : 1},
                        {question : "李敬是醜男嗎?", anser : 1},
                        {question : "李敬是醜男嗎?", anser : 1},
                        {question : "李敬是醜男嗎?", anser : 1}]

  判斷學生是否勾選異常 若有異常則顯示問題描述框
  function load_descript() 

  標頭基本資訊 無法新增或刪除 格式固定
  function head_content()

  
  按下新增問題按鈕觸發，打開新增問題欄位
  function open_Add_question()

  按下確定新增按鈕觸發
  新增表單問題 存進question表單 & 新增表單回答 存進answer 預設為 1

  按下刪除問題按鈕觸發，以紅色區塊標記可刪除區塊
  function open_Delete_question(bt)

  確定刪除問題
  function Delete_question

  function Add_question()
 
  

  載入學生及房東基本資料填寫欄位
  function load_form()
*/


var landlord_name = "" //房東姓名
var landlord_pnumber = "" //房東電話號碼
var student_addr = "" //學生租屋地址
var student_class = "" //學生班級
var student_ID = "" //學號
var student_name = "" //學生姓名
/*var question_table =[{question : "環境整潔", anser : -1},
                      {question : "讀書情形", anser : -1},
                      {question : "與房東相處", anser : -1},
                      {question : "賃居地點偏僻", anser : -1},
                      {question : "交友情形", anser : -1},
                      {question : "交通及生活機能", anser : -1},
                      {question : "進出人員是否複雜?有無安全疑慮?", anser : -1},
                      {question : "有無滅火器及會不會使用", anser : -1},
                      {question : "電熱器(太陽能)", anser : -1},
                      {question : "有無緊急逃生道及是否暢通", anser : -1},
                      {question : "有無緊急照明燈", anser : -1},
                      {question : "有無煙霧探測器", anser : -1}]*/
//判斷學生是否勾選異常 若有異常則顯示問題描述框
function load_descript()
{
    var radios = document.getElementsByClassName("radiotable")
    for(var i =0 ;i < radios.length;i++){
        var g = radios[i].getElementsByTagName("input")[0];
        var b = radios[i].getElementsByTagName("input")[1];   

        if(g.checked)
        {
            g.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
            console.log(g)
        }
        if(b.checked)
        {
            b.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
            console.log(b)
        }
        g.onclick = function(){
          this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group hid"
          console.log(g)
        }
        b.onclick = function(){
          this.parentNode.parentNode.getElementsByTagName("div")[0].className = "form-group"
          console.log(b)
        }
  }
}

function head_content(){
  if (typeof year == 'undefined') {
    year = "無";
  }
  if (typeof team == 'undefined') {
    team = "無";
  }

  var content = '<div class="col-sm-1"></div>'
  content += '<div  class="col-sm-10" style="margin-top: 50px">\
      <div class="col-sm-12 mtop" style="text-align:center">\
        <div class="col-sm-12"><label><h2>國立虎尾科技大學 '+ year +' 學年度第 '+ team +' 學期<br>賃居校外學生輔導訪問記錄表</h2></label></div>\
      </div>\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-6 mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">房東姓名</span>\
            <input  disabled  id="msg" type="text" class="form-control" value="'+ landlord_name +'" name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
        <div class="col-sm-6 mtop mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">房東電話</span>\
            <input disabled  id="msg" type="text" class="form-control" value="' + landlord_pnumber + '" name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
      </div>\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-12 mtop">\
          <div class="input-group mtop">\
            <span class="input-group-addon">賃居地址</span>\
            <input disabled  id="msg" type="text" class="form-control" value="' + student_addr + '"name="msg" placeholder="Additional Info">\
          </div>\
        </div>\
      </div>\
      <!-- 不給更改個人基本資料 -->\
      <div class="col-sm-12 mtop">\
        <div class="col-sm-2 mtop"><h4>賃居學生</h4>        </div>\
        <div class="col-sm-3 mtop">\
          <div class="input-group col-sm-12  mtop">\
            <span class="input-group-addon ">班級</span>\
            <input  disabled id="msg" type="text" class="form-control" value=""name="msg" placeholder="">\
          </div>\
        </div>\
        <div class="col-sm-4 mtop">\
          <div class="input-group col-sm-12 mtop">\
            <span class="input-group-addon">學號</span>\
            <input disabled  id="msg" type="text" class="form-control" value="' + student_ID + '"name="msg" placeholder="">\
          </div>\
        </div>\
        <div class="col-sm-3 mtop">\
          <div class="input-group col-sm-12 mtop">\
            <span class="input-group-addon">姓名</span>\
            <input disabled  id="msg" type="text" class="form-control" value="' + student_name+ '"name="msg" placeholder="">\
          </div>\
        </div>\
      </div>'
  return content;
}

//打開新增問題視窗
function open_Add_question(){
  document.getElementById("add_question_content").classList.remove("hid")
}

//確定新增問題按鈕
function Add_question(){    
  var msg = document.getElementById("question_content").value
  console.log(msg)
  question_table.push({question : msg, anser : -1})
  document.getElementById("add_question_content").classList.add("hid")
  load_student_form()
}
//打開刪除模式
var bt_flag = -1  //判斷按鈕是第幾次按下
function open_Delete_question(bt){
    if(bt_flag == -1){
        document.getElementById("add_bt").setAttribute("disabled","disabled")
        bt.innerHTML="取消刪除"
        var radios = document.getElementsByClassName("radiotable")
        for (var i = 0;i < radios.length;i++){
        radios[i].classList.add("delete_color")
        radios[i].onclick = Function("Delete_question(" + i + ")"); 
        }
        upload_imgs = document.getElementsByClassName("upload_img"); //取得上傳圖片按鈕
        change_number = document.getElementsByClassName("change_number") //取得題號順序按鈕
        for(var i = 0;i < upload_imgs.length;i++) //hid
        {
          upload_imgs[i].classList.add("hid");
          change_number[i].classList.add("hid");
        }
        alert("點選想要刪除的區塊")
    }else{
        load_student_form()
    }
    bt_flag *= -1
}
//確定刪除此問題
function Delete_question(i){
  var yes = confirm('確定要刪除問題\n ' + (i+1) + '.' + question_table[i].question  + '？');
  if (yes) {
      question_table.splice(i,1)
      load_student_form()
      bt_flag = -1
  } else {
      
  }
}

function changeNum(number){
    var i = prompt("輸入想置換的標題號")-1;
    if(!isNaN(i)){
        if(i < 0 || i >= question_table.length)
        {
            alert("標題號範圍在 " + 1 + " ~ " + (question_table.length))
        }else{
            c = question_table[i]
            question_table[i] = question_table[number]
            question_table[number] = c
            load_student_form()
        }
    }else{
        alert("只能輸入數字")
    }


}

function body_cntent(){
    var content = "";
    content += '\
      <div class="col-sm-12 mtop">\
      <div class="col-sm-12 mtop"><label>訪問項目與情形</label></div>'
    content += '<div class="col-sm-12">'
    content += '<button type="button" id="add_bt" onclick="open_Add_question()" class="mtop btn btn-success" style="bcakground:red;">增加問題</button>  '
    content += '<button type="button" id="delete_bt" onclick="open_Delete_question(this)" class="mtop btn btn-danger" >刪除問題</button>&nbsp;'
    content += '<button type="button" id="delete_bt" onclick="post_question_table()"  class="mtop btn btn-success" >確認修改表單</button>'
    content += '</div>'
    content += '<div class="col-sm-12 hid" id="add_question_content"><br>\
                    <div class="col-sm-12"><label>輸入問題: <label></div>\
                    <div class="col-sm-12"><textarea type="text" id="question_content" class="form-control" rows="2" id="comment"></textarea></div>\
                    <div class="col-sm-12" style="text-align:right;"><br><button onclick="Add_question()" type="button" class="btn btn-info">確定新增</button></div>\
                </div>'

    for(var i =0;i < question_table.length;i++)
    {
      var normal = ""
      var abnormal = ""
      if(question_table[i].anser == 1){
          normal = "checked"
      }else if(question_table[i].anser == 0) {
          abnormal = "checked"
      }
      
      content += '\
            <div class="col-sm-6 mtop radiotable" >\
                <label class="col-sm-12">&nbsp;&nbsp;' + (i+1) + "." + question_table[i].question +'</label>\
                <div class="col-sm-12">&nbsp&nbsp<label class="radio-inline"><input disabled type="radio" name="optradio' + i +'"' + normal + '>良好</label>\
                <label class="radio-inline"><input disabled  type="radio" name="optradio' + i + '"'+ abnormal + '>異常</label>&nbsp;\
                &nbsp&nbsp<button type="button" class="btn btn-info upload_img">上傳圖片</button>&nbsp&nbsp&nbsp&nbsp&nbsp\
                <button onclick="changeNum(' + i +')" style="background:white;border:0;color:blue;" class="change_number"><span class="glyphicon glyphicon-refresh"></span>&nbsp&nbsp&nbsp題號順序</button>\
                <div class="form-group hid" name="textarea" id="dsp">\
                    <label for="comment">描述異常狀況:</label>\
                    <textarea readonly class="form-control" rows="2" id="comment"></textarea></div>\
                </div>\
            </div>'
    }
    content += "</div>"

    //訪問要點及學生意見 跟 儲存送出鈕
    content += '<div class="col-sm-12 mtop">\
                    <div class="col-sm-12 mtop">\
                        <div class="form-group">\
                        <label>訪問要點及學生意見</label>\
                        <textarea  readonly  class="form-control" style="height:100px;resize:none;" id="comment"></textarea>\
                        </div>\
                    </div>\
                </div>'
    return content;
}


//載入學生及房東基本資料填寫欄位
function load_student_form(){ 
      var form_content = ""
      form_content += head_content();
      form_content += body_cntent();                    
      document.getElementById("student_form").innerHTML = form_content;
      load_descript()
} 


//傳 question_table 給 Class_modify.php

function post_question_table()
{

  $.ajax({
    type:'post',
    url: '../class/Class_modify.php',
    data:{
      question_table:question_table,
      formtype:formtype
    },
    success:function(res){
      if(res){
        alert('修改成功');
        window.location.href = "ManageTable.php";
      }
      else{
        alert('修改失敗, 請聯絡網站管理員');
      }
    }
  })
}

