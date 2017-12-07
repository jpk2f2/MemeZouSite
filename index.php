<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mizzou Memes</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="mutiger.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="FileSaver.js"></script>
    <script src="https://not-an-aardvark.github.io/snoowrap/snoowrap-v1.js"></script>
    <script src="UploadToReddit.js"></script>

    <style>
       #image {
            left: -10000px;
            top: -10000px;
            position: absolute;
            display: block;
        }

        input {
            display: block;
        }

        body {
            margin: 20px;
        }

        canvas {
            border: 10px dotted gray;
        }
        .page-header {
            margin-top: 0px;
        }
        body {
            margin-top: 1%;
        }
    </style>

</head>


<body>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="page-header" onchange="onFileSelected(event)">
              <img src="memesbanner.jpg" alt="banner goes here" style="width: 100%"></img>
                <!--<h1>Mizzou Meme Maker</h1>-->
            </div>
            <div class="text-center">
                <input id="memeTitle" style="text-align: center;" type="text" class="form-control" placeholder="Title of Picture or Post" />
                <br>
            </div>

            <div class="text-center">
                <canvas id="meme">
                     Sorry, canvas is not supported.
                </canvas>
            </div>

            <br>

            <div class="text-center">
                <label class="btn btn-primary btn-file" style="margin-top: 5px; margin-bottom: 5px;">
                   <input onchange="onFileSelected(event)" type="file" name="myImage" style="display: none;" accept="image/x-png,image/gif,image/jpeg">Select Image
                   <br>
                </label>
                    <select name ="list" onchange="getPicture(this.value)">   

                        <?php
                           // $link = mysqli_connect("localhost", "root","","meme_site");
                            $link = mysqli_connect("localhost", "jkayser","","meme_site");


                            if(!$link){
                                echo "Error: unable to connect to MySQL";
                                exit;
                            }

                            $stmt = mysqli_prepare($link, "SELECT id,title FROM Pictures");
                            mysqli_stmt_execute($stmt);

                            mysqli_stmt_bind_result($stmt, $id, $title);

                            while(mysqli_stmt_fetch($stmt)){
                               //echo "<li><a href='memes/","$id",".jpg' onchange='getPicture($id)'>$title</a></li>";
                               // echo "<li onclick='getPicture($id)'>$title</li>";
                                echo "<option value='$id'>$title</option>";
                            }

                            //echo json_encode($id);
                            mysqli_stmt_close($stmt);
                            mysqli_close($link);

                        ?>

                    </select> 
            </div>

            <div class="text-center">
                <img class="img-responsive center-block" id="image" alt="success" />
            </div>
            <span style="margin-top: 5px; margin-bottom: 5px;">
              <br>
              <input class="form-control" type="text" style="text-align: center;" placeholder="Top Text" id="top-input" />
              <br>
              <input class="form-control" type="text" style="text-align: center;" placeholder="Bottom Text" id="bottom-input" />
              <br>
            </span>

            <span class="text-center" style="display: block; margin: 0 auto;">
                
                <button name="Download Image" value="Download Image" class ="btn btn-primary" onclick="download()">Download Image</button>
                <!-- <button name="Save Image" value="Save Image" class="btn btn-primary" onclick="save2()">Upload Image</button> -->
                <button name="Save Image" value="Save Image" class="btn btn-primary" onclick="location.href = 'upload.php';">Upload Image</button>
                <button name="Upload Imgur" value="Upload Imgur" class="btn btn-primary" onclick="uploadToImgur()">Upload to Imgur</button>
                <button name="Post Reddit" value="Post Reddit" class="btn btn-primary" onclick="postToReddit()">Post to Reddit</button>
            </span>

            <span class="text-center">
                <h1><a hidden="true" href="" target="_blank" id="redditLink">View on Reddit</a></h1>
            </span>

            <script>
               // window.onload = function() {
                 //   get_names();
                //};
                var memeSize = 300;
                var canvas = document.getElementById('meme');
                context = canvas.getContext('2d');
                canvas.width = memeSize;
                canvas.height = memeSize;
                var img = document.getElementById('image');
                var topInput = document.getElementById('top-input');
                var bottomInput = document.getElementById('bottom-input');
                img.onload = function() {
                    memeify()
                }
                topInput.addEventListener('keydown', memeify);
                topInput.addEventListener('keyup', memeify);
                topInput.addEventListener('change', memeify);
                bottomInput.addEventListener('keydown', memeify);
                bottomInput.addEventListener('keyup', memeify);
                bottomInput.addEventListener('change', memeify);
                function uploadToImgur() {
                    try {
                        var img = document.getElementById('meme').toDataURL('image/jpeg', 0.9).split(',')[1];
                    } catch (e) {
                        var img = document.getElementById('meme').toDataURL().split(',')[1];
                    }
                    $.ajax({
                        url: 'https://api.imgur.com/3/image',
                        type: 'post',
                        headers: {
                            Authorization: 'Client-ID 53430f163e6983d'
                        },
                        data: {
                            image: img
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                //upload(response.data.link, document.getElementById('memeTitle').value);
                                window.location = response.data.link;
                            } else {
                                alert('Upload Failed');
                            }
                        }
                    });
                }
                function postToReddit() {
                    try {
                        var img = document.getElementById('meme').toDataURL('image/jpeg', 0.9).split(',')[1];
                    } catch (e) {
                        var img = document.getElementById('meme').toDataURL().split(',')[1];
                    }
                    $.ajax({
                        url: 'https://api.imgur.com/3/image',
                        type: 'post',
                        headers: {
                            Authorization: 'Client-ID 53430f163e6983d'
                        },
                        data: {
                            image: img
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                upload(response.data.link, document.getElementById('memeTitle').value);
                                //window.location = response.data.link;
                            } else {
                                alert('Upload Failed');
                            }
                        }
                    });
                }
                function get_names(){
                    $.ajax({
                        type: 'POST',
                        url: 'getNames.php',
                        cache: false,
                    });        
                }
                function search(){
                    var temp = document.getElementById("searchid").value;
                    var searchString = 'search_string='+temp;
                    if(temp == ''){
                        alert("Nothing entered");
                    }else{
                        $.ajax({
                            type: 'POST',
                            url: 'search.php',
                            data: searchString,
                            cache: false,
                            success: function(result){
                                var id = result;
                            }
                        });
                    }
                }
                function save2() {
                    $.ajax({
                        type: 'POST',
                        url: 'upload.php',
                        data: searchString

                        //}
                        //success: function(result) {
                          //  alert(result);
                            //$("p").text(result);
                       // }
                    });
                }
                function download() {
                    var title = document.getElementById("memeTitle").value;
                    var canvas = document.getElementById('meme');
                    canvas.toBlob(function(blob) {
                        saveAs(blob, title + ".png");
                    });
                }
                function getPicture(id) {
                    var x = id;
                    var imgtag = document.getElementById("meme");
                    var ctx = imgtag.getContext("2d");
                    var img = new Image();
                    img.onload = function() {
                       ctx.drawImage(img, 0, 0);
                        //var loc = 
                        
                    };
                    //img.src = 'http://craftycat3.ddns.net/memes/'+x+'.jpg';
                    img.src = 'https://mizzoumememaker.com/memes/'+x+'.jpg';
                   // memeify();
                    }                
                function onFileSelected(event) {
                    var file = event.target.files[0];
                    var reader = new FileReader();
                    var imgtag = document.getElementById('image');
                    imgtag.title = file.name;
                    reader.onload = function(event) {
                        imgtag.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                    memeify();
                }
                function memeify() {
                    context.clearRect(0, 0, canvas.wdith, canvas.height);
                    context.drawImage(img, 0, 0, memeSize, memeSize);
                    context.lineWidth = 4;
                    context.font = '20pt sans-serif';
                    context.strokeStyle = 'black';
                    context.fillStyle = 'white';
                    context.textAlign = 'center';
                    context.textBaseline = 'top';
                    var text1 = document.getElementById('top-input').value;
                    text1 = text1.toUpperCase();
                    x = memeSize / 2;
                    y = 0;
                    wrapText(context, text1, x, y, 300, 28, false);
                    context.textBaseline = 'bottom';
                    var text2 = document.getElementById('bottom-input').value;
                    text2 = text2.toUpperCase();
                    y = memeSize;
                    wrapText(context, text2, x, y, 300, 28, true);
                }
                function wrapText(context, text, x, y, maxWidth, lineHeight, fromBottom) {
                    var pushMethod = (fromBottom) ? 'unshift' : 'push';
                    lineHeight = (fromBottom) ? -lineHeight : lineHeight;
                    var lines = [];
                    var y = y;
                    var line = '';
                    var words = text.split(' ');
                    for (var n = 0; n < words.length; n++) {
                        var testLine = line + ' ' + words[n];
                        var metrics = context.measureText(testLine);
                        var testWidth = metrics.width;
                        if (testWidth > maxWidth) {
                            lines[pushMethod](line);
                            line = words[n] + ' ';
                        } else {
                            line = testLine;
                        }
                    }
                    lines[pushMethod](line);
                    for (var k in lines) {
                        context.strokeText(lines[k], x, y + lineHeight * k);
                        context.fillText(lines[k], x, y + lineHeight * k);
                    }
                }
            </script>
        </div>
    </div>
</body>
