# Bible-in-a-year

Read the KJV in a year kindle project.

- Old_New - contains 3 chapters of the Old Testament and 1 chapter of the New Testament.
- McCheyne - The McCheyne Bible reading schedule
- Suomi - The Bible in Finnish (todo)

## Creating your own reading plan

- Bring up the api environment

```shell
docker-compose up
```

- Curl the api endpoint and format into html
- Create table of contents
- Build the kindle version
- Upload your mobi to kindle

## How to build the kindle version

### Download and install KindleGen

[KindleGen](https://www.amazon.com/gp/feature.html?docId=1000765211)

### Clone the repo

```shell
git clone git@github.com:andrewwippler/Bible-in-a-year.git
```

### Navigate to the folder

```shell
cd Bible-in-a-year/kindle/McCheyne
```

### Run KindleGen

```shell
/path/to/kindlegen KJVinaYear.opf -c2 -verbose -o McCheyne.mobi
```

(optional) Use Calibre to convert to another format

# Licenses
King James Bible - Public Domain  
Pyha Raamattu 1776 - Public Domain  
My Organizations of the text and subsequent work - MIT
