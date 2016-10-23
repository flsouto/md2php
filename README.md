# md2php


## What is this

This is a command line tool that allows you to extract php snippets from a markdown file and save these snippets in a directory. In order for a snippet to be matched and extracted from the markdown it must be in a code block starting with <?php or <?.

## Installation

Install this tool via composer:

```
composer require flsouto/md2php
```

## Usage

First create a directory that will store all the snippets extracted from the markdown file:

```
$ mkdir snippets
```

Then call the extract program informing the markdown file to extract from and the directory to write to:

```
$ php vendor/flsouto/md2php/extract.php README.md ./snippets
```

As an example, I used the command above for processing the documentation of one of my repositories: [README.md](https://raw.githubusercontent.com/flsouto/array2options/master/README.md)

The output was:

```
Extracting snippet to ./snippets/01-array2options.php 
Extracting snippet to ./snippets/02-selecting_option.php 
Extracting snippet to ./snippets/03-using_associative_arrays.php 
Extracting snippet to ./snippets/04-converting_datasets_options.php 
Total snippets extracted: 4
```

Notice how the snippets are named in order of appearence in the document. Notice also that it is using the last heading matched before reaching the snippet as name for the file.

After doing that you can simply run an extracted snippet from the terminal itself:

```
$ php snippets/01-array2options.php 
```

## Final thoughts

I find this tool useful when I am writing README documents such as this one because I can run the demonstration code blocks along the writing without having to be copying and pasting snippets all the time. 