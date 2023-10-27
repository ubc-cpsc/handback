This is for UBC CPSC course accounts to use.

## Setup

Simply:
```
cd ~/public\_html
git clone https://github.com/ubc-cpsc/handback.git myhandback
cd myhandback
chmod 750 ./
chmod 640 .htaccess
cp handback.cfg.default handback.cfg
```

Then, customize handback.cfg. In particular you must set `handbackDir` and `gradesCSV`.

You will need to create whatever you set as $handbackDir

By default, users can download files that have '%userid%' in the filename where 'userid' is the user's login id (CWL).
(See the definition of $allowed_filenames in index.php, and note that you can redefine that in handback.cfg if you like.)

## Usage

### Instructor/TA

Upload files to the directory specified by `handbackDir` in handback.cfg.

Upload grades to the file specified by `gradesCSV` in handback.cfg

