## OneShell
- One Shell to rule them all, One Shell to find them,
- One Shell to bring them all and in the darkness bind them

Status: ***Experimental*** / Version: **v0.1**

---
**Features**

 1. System information
 2. Execute commands
 3. Crashes your system

**Todo**

 1. Write a PHP obfuscator, minifier and HTML/CSS/JS minifier
 2. Write something that makes it look like shit on attacked boxes
 3. Reverse shells menu needs to work
 4. SQL browser
 5. File manager is not finished
 6. Pretty much the whole thing.

**How the hell are you compiling this/making it one big PHP file?**
Compiling is recursive inclusion, eventually resulting in one big PHP file. This works by replacing all include_or_detect calls in bootstrap.php with their corresponding PHP files, and then doing this with each one of those files until there is nothing else to include.

---

This was an experiment.

- I am not responsible for you using this in any environment on any box.
- I am not responsible for anything that you do with this shell.
- I am not responsible if you get caught doing stuff you shouldn't be with this shell.

---

### Credits:

 * Mephisto (me)
 * Fuhosin and it's creators
