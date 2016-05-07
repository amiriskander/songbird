# Chapter 3: Creating the Dev Environment

So that we speak the same language throughout the book, we need a dev (development) environment that it is consistent in everyone's host. That best way to do that is to create a virtual machine (VM) via [vagrant](https://www.vagrantup.com). This is a popular technique nowadays. Laravel has something similar called [Homestead](https://github.com/laravel/homestead)

We will do **actual coding in your host** (main operating system) and let the VM run the web server, sharing the web folder between the 2 machines. Note that 99% of the time, you don't need to touch the VM except to make sure that it is up and running.

Our VM will be running ubuntu 14.04, Apache2 and PHP 5.6.

## Objectives

> * Install the standard SongBird virtual machine.

> * Configure your host to access the VM.


## Installation

* In your host machine, make sure you have [php 5.6](http://php.net/manual/en/install.php) and timezone configured, [vagrant](https://www.vagrantup.com/downloads.html), [virtualbox](https://www.virtualbox.org/wiki/Downloads) and [git](https://git-scm.com) **installed**. 

* Mac users can install php 5.6 using

```
-> curl -s http://php-osx.liip.ch/install.sh | bash -s 5.6
-> echo "export PATH=/usr/local/php5/bin:$PATH" >> ~/.profile
-> sudo vi /usr/local/php5/php.d/99-liip-developer.ini
# add the date.timezone settings to your preferred timezone. eg
# date.timezone = Australia/Melbourne
# then shutdown the terminal and restart again
```

* If you are on Windows OS install [NFS support plugin](https://github.com/GM-Alex/vagrant-winnfsd)

* Login in [github](http://github.com) and [fork](https://help.github.com/articles/fork-a-repo/) [SongBird repo](https://github.com/bernardpeh/songbird)

```
# Now you want to clone your new forked repo under your home dir.
-> cd ~
-> git clone git@github.com:your_username/songbird.git
```

* run vagrant

```
# now we are going to bring up the virtual machine. This should take up to 15 to 30 mins depending on your internet connnection. Have a cup of coffee.
# if you already have an instance of ubuntu 14.04, remember to do a vagrant box update.

-> cd songbird
-> vagrant up

Bringing machine 'default' up with 'virtualbox' provider...
==> default: Importing base box 'puphpet/ubuntu1404-x64'...
==> default: Matching MAC address for NAT networking...
==> default: Checking if box 'puphpet/ubuntu1404-x64' is up to date...
==> default: A newer version of the box 'puphpet/ubuntu1404-x64' is available! You currently
==> default: have version '2.0'. The latest is version '20151201'. Run
==> default: `vagrant box update` to update.
==> default: Setting the name of the VM: songbird_default_1462590898969_15135
==> default: Pruning invalid NFS exports. Administrator privileges will be required...
...
```

* If there were some modules that were not installed, reprovision it

```
vagrant reload --provision
```


* Once everything is successful, we can now update the config for the app:

```
-> cd www/songbird
-> composer udpate

# when prompted, leave default settings except for the followings:
# database_host: 192.168.56.111
# database_name: songbird
# database_user: songbird
# database_password: songbird
...
# mailer_host: 127.0.0.1:1025
...
```

We are using smtp port 1025 to catch all mails.

* add IP of your VM on your [host file](http://www.rackspace.com/knowledge_center/article/how-do-i-modify-my-hosts-file)

```
192.168.56.111 songbird.dev  www.songbird.dev
```

* Open up browser and go to http://songbird.dev/app/example. If you see the word "homepage", your installation is successful.

* Now try this url http://songbird.dev/app_dev.php/app/example and you should see the same "homepage" text but with a little icon/toolbar at the bottom of the page. That's right, you are now in dev mode.

Why the "app_dev.php"? That is like the default index page for the dev environment. All url rewrite goes to this page and this is something unique to Symfony.  

A bit of command line teaser...

```
-> vagrant ssh -c "wget http://songbird.dev/config.php; cat config.php; rm config.php"
```

We shelled into the VM and look at the raw html of http://songbird.dev/config.php. If you see "Your configuration looks good to run Symfony.", you can be sure that the VM is setup correctly.

## Summary

In this chapter, we setup the development environment from a ready made instance of virtualbox. We installed Symfony and configured the host file to access SongBird from the host machine.

Next Chapter: [iChapter 4: The Testing Framework Part 1](https://github.com/bernardpeh/songbird/tree/chapter_4)

Previous Chapter: [Chapter 2: What is SongBird](https://github.com/bernardpeh/songbird/tree/chapter_2)

## Exercises (Optional)

* Try running Symfony's build-in webserver. What command would you use?

* Try running vagrant with config generated from [PuPHPet](https://puphpet.com/).

* Try using [docker](https://www.docker.com/) rather than [vagrant](https://www.vagrantup.com) for development. What are the pros and cons of each method?

* How many ways are there to install Symfony?

## References

* [Symfony Installation](https://symfony.com/doc/current/book/installation.html)


