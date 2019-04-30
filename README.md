# Bible-in-a-year

Read the KJV in a year kindle project.

- Old_New - contains 3 chapters of the Old Testament and 1 chapter of the New Testament.
- McCheyne - The McCheyne Bible reading schedule
- Suomi - The Bible in Finnish (todo)

## Creating your own reading plan

- Download the linux version of [KindleGen](https://www.amazon.com/gp/feature.html?docId=1000765211)
- extract the `kindlegen` file and place it in `api/docker/`
- Bring up the api environment

```shell
docker-compose up
```
Visit http://localhost:8080/kindleGen.php and upload a CSV with a length of 365 or 366 rows.
Download the generated file and upload it to your kindle.

(optional) Use Calibre to convert to another format

# Licenses
King James Bible - Public Domain  
Pyha Raamattu 1776 - Public Domain  
My Organizations of the text and subsequent work - MIT
