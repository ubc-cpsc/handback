This is for UBC CPSC course accounts to use.

## Setup

Simply:
```
cd ~/public\_html
git clone https://github.com/ubc-cpsc/handback.git myhandback
cd myhandback
# Check that you have execute permissions for `setup.sh`
ls -l setup.sh
./setup.sh
```
Then, customize handback.cfg. In particular you must set `handbackDir` and `gradesCSV`. This is where handback will look for handback files and grades.
Note: It is strongly reccomended that you keep any student information (i.e. handback files and grades) out of the `~/public_html` directory.

You will need to create the directory you set as $handbackDir

Note: By default, users can download files that have '%userid%' in the filename where 'userid' is the user's login id (CWL).
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

Students will see all rows that have `cwlid` matching their CWL ID.

For example, student-1 will see only:
| cwlid     | assignment | grade |
| --------- | ---------- | ----- |
| student-1 | lab1       | 100%  |
| student-1 | lab2       | 100%  |

