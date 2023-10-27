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

#### Handback Files
Upload files to the directory specified by `handbackDir` in handback.cfg.

#### Handback Grades
Upload grades to the file specified by `gradesCSV` in handback.cfg

The csv must contain a column named `cwlid` that contains the CWL ID. This is what the script will use to distribute grades.

For example, you may use a csv of the form:

```
cwlid,assignment,grade
student-1,lab1,100%
student-1,lab2,100%
student-2,lab1,99%
student-2,lab2,99%
```

student-1 will see:
```
{"cwlid":"student-1","assignment":"lab1","grade":"100%"}
{"cwlid":"student-1","assignment":"lab2","grade":"100%"}
```
