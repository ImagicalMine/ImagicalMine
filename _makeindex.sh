#!/bin/bash
# BASH script designed to generate index files using 
# Twitter Bootstrap [getbootstrap.com]

# Notes:
# * Script will ignore "img" and "css" directories and "index.html" file
# * Remember to copy Twitter Bootstrap files (css,img)

# Sample usage:
# cd /var/www/downloads
# make_index.sh

# web root directory, 
# it will be used to generate URLs
# ""           - root directory
# "/downloads" - '/downloads' subdirectory
root_directory="/home/travis/build/ImagicalMine"

# template settings
brand="sleeplessbeastie.eu"
title="sleeplessbeastie.eu - briefcase"

# generate header part of the HTML file
function header {
  cat << EOF
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>$title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="$root_directory/css/bootstrap.min.css" rel="stylesheet">
    <link href="$root_directory/css/bootstrap-custom.css" rel="stylesheet">
    <link href="$root_directory/css/bootstrap-responsive.min.css" rel="stylesheet">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <span class="brand">$brand</span>
        </div>
      </div>
    </div>

    <div class="container"> 
      <div class="page-header"><h1>$title</h1></div>
EOF
}

# generate footer part of the HTML file
function footer {
  cat << EOF
    </div> <!-- /container -->
  </body>
</html>
EOF
}


# create index file for each directory (ignore ./css and ./img directories)
find . -type d -not -path "*/img" -not -path "*/css" | while read directory; do
  # remove "./" from the beginning of the path
  directory=${directory#./}
  
  # index file
  index_file=$directory/index.html

  # create/truncate index file
  : > $index_file

  # append header to the index file
  header >> $index_file

  # append "Up to higher level directory" link
  echo "<div class=\"row\"><div class=\"span12\">" >> $index_file
  if [ "$directory" != "." ]; then
    echo "<h2 class=\"muted\">${directory}</h2><hr/>" >> $index_file
    echo "<i class=\"icon-arrow-up\"></i> <a href=\"../index.html\">Up to higher level directory</a>" >> $index_file
  else
    echo "<h2 class=\"muted\">root directory</h2><hr/>" >> $index_file
    echo "<i class=\"icon-arrow-up\"></i> Up to higher level directory" >> $index_file
  fi
  echo "</div></div>" >> $index_file

  # append empty row
  echo "<div class=\"row\"><div class=\"span12\"><br/></div></div>" >> $index_file

  # generate content
  content=""
  while read file
  do
    if [ -d "$directory/$file" ]; then
      content+="<div class=\"row\">"
      if [ "$directory" == "." ]; then
        content+="<div class=\"span12\"><div class=\"directory\"><a href=\"$root_directory/$file/index.html\">$file</a></div></div>"
      else
        content+="<div class=\"span12\"><div class=\"directory\"><a href=\"$root_directory/$directory/$file/index.html\">$file</a></div></div>"
      fi    
      content+="<div class=\"span12\"><div class=\"type\"><i class=\"icon-folder-open\"></i> Directory</div></div>"
      content+="</div>"
      content+="<hr/>"            
    elif [ -f "$directory/$file" ]; then
      file_type=$(file -b "$directory/$file")
      file_size=$(du -h "$directory/$file" | cut -f1)
      content+="<div class=\"row\">"
      if [ "$directory" == "." ]; then
        content+="<div class=\"span10\"><div class=\"file\"><a href=\"$root_directory/$file/index.html\">$file</a></div></div>"
      else
        content+="<div class=\"span10\"><div class=\"file\"><a href=\"$root_directory/$directory/$file/index.html\">$file</a></div></div>"
      fi    
      content+="<div class=\"span2\"><div class=\"type\"><i class=\"icon-hdd\"></i> $file_size</div></div>"      
      content+="</div>"      
      content+="<div class=\"row\">"            
      content+="<div class=\"span12\"><div class=\"type\"><i class=\" icon-info-sign\"></i> $file_type</div></div>"                  
      content+="</div>"      
      content+="<hr/>"      
    fi
  done < <(ls -1 --group-directories-first $directory --ignore=index.html --ignore=css --ignore=img --ignore=make_index.sh)

  # append content to the index file
  echo $content >> $index_file

  # append footer to the index file
  footer >> $index_file
done
