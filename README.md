This is for UBC CPSC course accounts to use.

Simply:
```
cd ~/public\_html
git clone https://github.com/ubc-cpsc/handback.git myhandback
cd myhandback
chmod 750 ./
chmod 640 .htaccess
cp handback.cfg.default handback.cfg
```

Then, customize handback.cfg.

You will need to create whatever you set as $handbackDir

By default, users can download files that have '%userid%' in the filename where 'userid' is the user's login id (CWL).
(See the definition of $allowed_filenames in index.php, and note that you can redefine that in handback.cfg if you like.)
