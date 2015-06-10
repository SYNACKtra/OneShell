import sys

# this recursively replaces include_or_detect with PHP source files
# use this for compiling the shell into one file

global main_file
main_file = ""

def replace_file(file):

	global main_file
	lines = open(file).readlines()

	for line in lines:
		stripped = line.rstrip().replace("\t", "").replace(" ", "").replace("<?php", "").replace("?>", "");
		if "include_or_detect" in stripped and "\"" in stripped:
			args = stripped.replace("include_or_detect(", "").replace("\"","").replace(");", "")
			split_args = args.split(",")
			next_file = "src/" + split_args[0] + "/" + split_args[1] + ".php"
			next_file = next_file.replace("//", "/") # when args[0] is empty string
			if not "<?php" in line:
				main_file = main_file + "?>"
			main_file = main_file + "<?php\n//IMPORT " + next_file + "//\n?>"
			replace_file(next_file)
			if not "<?php" in line:
				main_file = main_file + "<?php"
		else:
			main_file = main_file + line

# start at src/bootstrap.php
replace_file("src/bootstrap.php")
main_file = main_file.replace("<?php?>", "").replace("?><?php", "").replace("\n\n", "\n")

if len(sys.argv) != 2:
	print("Usage: python " + sys.argv[0] + " <out file>")
	exit()

output = open(sys.argv[1], "w")
output.write(main_file)
output.close()
print("Written " + str(len(main_file)) + " lines to " + sys.argv[1])