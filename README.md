This is for UBC CPSC course accounts to use.

Simply:
cd ~/public\_html
git clone https://bitbucket.org/UBCCS/handback.git myhandback
cd myhandback
chmod 750 ./
chmod 640 .htaccess
cp handback.cfg.default handback.cfg

Then, customize handback.cfg
You will need to create whatever you set as $handbackDir
