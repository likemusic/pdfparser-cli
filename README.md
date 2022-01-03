# PDFPARSER-CLI

CLI wrapper for [smalot/pdfparser](https://github.com/smalot/pdfparser).

## Usage

```
# php src/phpparser-cli.php

Usage:
  command [options] [arguments]
  
```

```
# php src/phpparser-cli.php get-text --help
Description:
  Get text from pdf-file.

Usage:
  get-text [options] [--] <filename> [<pages>]

Arguments:
  filename              Pdf file name
  pages                 Comma separated page numbers [default: "all"]

Options:
  -o, --of=OF           Output filename. By default just print text to console.
```
