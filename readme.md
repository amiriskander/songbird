# Chapter 10: BDD With Codeception (Optional)

This chapter is optional, feel free to skip it if you already have your own testing framework in place.

Behavioural-Driven-Development (BDD) is best used as [integration testing](https://en.wikipedia.org/wiki/Integration_testing). It is the concept of writing tests based on user's behaviour. The way users interact with the software defines the requirements for the software. Once we know the requirements, we are able to write tests and simulate user's interaction with the software.

In BDD, each user's requirement (user story) can be created using the following template:

```
As a ...
I (don't) want to ...
So that ...
```

We can then further breakdown the story into scenarios. For each scenario, we define the "When" (user's action) and the "Then" (acceptance criteria).

```
Given scenario
When ...
Then ...
```

It is a good idea to create a matrix for user stories and test scenarios to fully capture user's requirement as part of the functional specifications.

## Objectives

> * User Stories
> * User Scenarios
> * Creating the Cest Class

## Pre-setup

Make sure we are in the right branch. Let us branch off from the previous chapter.

```
# check your branch
-> git status
# start branching now
-> git checkout -b my_chapter10
```
## User Stories

Let us define the user stories for this chapter. We will define the user stories before each chapter from now on.

**User Story 10: User Management**

<table>
<tr><td><strong>Story Id</strong></td><td><strong>As a</strong></td><td><strong>I</strong></td><td><strong>So that I</strong></td></tr>
<tr><td>10.1</td><td>test1 user</td><td>want to login</td><td>can access admin functions</td></tr>
<tr><td>10.2</td><td>admin user</td><td>want to login</td><td>can access admin functions</td></tr>
<tr><td>10.3</td><td>test3 user</td><td>don't want to login</td><td>can prove that this account is disabled</td></tr>
<tr><td>10.4</td><td>test1 user</td><td>want to manage my own profile</td><td>can update it any time</td></tr>
<tr><td>10.5</td><td>test1 user</td><td>dont't want to manage other profiles</td><td>don't breach security</td></tr>
<tr><td>10.6</td><td>admin user</td><td>want to manage all users</td><td>can control user access of the system</td></tr>
</table>

## User Scenarios

We will break the individual story down with user scenarios.

<strong>Story ID 10.1: As a test1 user, I want to login, so that I can access admin functions.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.1.1</td><td>Wrong login credentials</td><td>I login with the wrong credentials</td><td>I should see an error message</td></tr>
<tr><td>10.1.2</td><td>See my dashboard content</td><td>I login correctly</td><td>I should see Access Denied</td></tr>
<tr><td>10.1.3</td><td>Logout successfully</td><td>I go to the logout url</td><td>I should be redirected to the home page</td></tr>
<tr><td>10.1.4</td><td>Acess admin url without logging in</td><td>go to admin url without logging in</td><td>I should be redirected to the login page</td></tr>
</table>

<strong>Story ID 10.2: As a admin user, I want to login, so that I can access admin functions.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.2.1</td><td>Wrong login credentials</td><td>I login with the wrong credentials</td><td>I should see an error message</td></tr>
<tr><td>10.2.2</td><td>See my dashboard content</td><td>I login correctly</td><td>I should see the text User Management</td></tr>
<tr><td>10.2.3</td><td>Logout successfully</td><td>I go to the logout url</td><td>I should be redirected to the home page</td></tr>
<tr><td>10.2.4</td><td>Acess admin url without logging in</td><td>go to admin url without logging in</td><td>I should be redirected to the login page</td></tr>
</table>

<strong>Story ID 10.3: As a test3 user, I don't want to login successfully, so that I can prove that this account is disabled.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.3.1</td><td>Account disabled</td><td>I login with the right credentials and access test3 profile page</td><td>I should see an "account disabled" message</td></tr>
</table>

<strong>Story ID 10.4: As a test1 user, I want to manage my profile, so that I can update it any time.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.4.1</td><td>Show my profile</td><td>I go to "/admin/?action=show&entity=User&id=2"</td><td>I should see test1@songbird.app</td></tr>
<tr><td>10.4.2</td><td>Hid uneditable fields</td><td>I go to "/admin/?action=edit&entity=User&id=2"</td><td>I should not see enabled, locked and roles fields</td></tr>
<tr><td>10.4.3</td><td>Update Firstname Only</td><td>I go to "/admin/?action=edit&entity=User&id=2" And update firstname only And Submit</td><td>I should see content updated</td></tr>
<tr><td>10.4.4</td><td>Update Password Only</td><td>I go to "/admin/?action=edit&entity=User&id=2" And update password And Submit And Logout And Login Again</td><td>I should see content updated And be able to login with the new password</td></tr>
</table>

<strong>Story ID 10.5: As a test1 user, I don't want to manage other profiles, so that I don't breach security.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.5.1</td><td>List all profiles</td><td>I go to "/admin/?action=list&entity=User" url</td><td>I should get an "access denied" error.</td></tr>
<tr><td>10.5.2</td><td>Show test2 profile</td><td>I go to "/admin/?action=show&entity=User&id=3"</td><td>I should get an "access denied" error.</td></tr>
<tr><td>10.5.3</td><td>Edit test2 user profile</td><td>I go to "/admin/?action=edit&entity=User&id=3"</td><td>I should get an "access denied" error</td></tr>
<tr><td>10.5.4</td><td>See admin dashboard content</td><td>I login correctly</td><td>I should not see User Management Text</td></tr>
</table>

<strong>Story ID 10.6: As an admin user, I want to manage all users, so that I can control user access of the system.</strong>

<table>
<tr><td><strong>Scenario Id</strong></td><td><strong>Given</strong></td><td><strong>When</strong></td><td><strong>Then</strong></td></tr>
<tr><td>10.6.1</td><td>List all profiles</td><td>I go to "/admin/?action=list&entity=User" url</td><td>I should see a list of all users in a table</td></tr>
<tr><td>10.6.2</td><td>Show test3 user</td><td>I go to "/admin/?action=show&entity=User&id=4" url</td><td>I should see test3 user details</td></tr>
<tr><td>10.6.3</td><td>Edit test3 user</td><td>I go to "/admin/?action=edit&entity=User&id=4" url And update lastname</td><td>I should see test3 lastname updated on the "List all users" page</td></tr>
<tr><td>10.6.4</td><td>Create and Delete new user</td><td>I got to "/admin/?action=new&entity=User" And fill in the required fields And Submit And Delete the new user</td><td>I should see the new user created and deleted again in the listing page.</td></tr>
</table>

## Creating the Cest Class

Since we have already deleted the test directory, let us create a new test dir.

```
-> cd src/AppBundle
-> ../../bin/codecept bootstrap
-> ../../bin/codecept build
```

and update the acceptance file again

```
# src/AppBundle/tests/acceptance.suite.yml
class_name: AcceptanceTester
modules:
    enabled:
        - WebDriver:
            url: 'http://songbird.app'
            browser: chrome
            window_size: 1024x768
            capabilities:
                unexpectedAlertBehaviour: 'accept'
                webStorageEnabled: true
        - \Helper\Acceptance
```

Codeception is really flexible in the way we create the test scenarios. Take User Story 1 for example, we will break the user story down into directories and the scenario into cest class:

```
-> bin/codecept generate:cest acceptance As_Test1_User/IWantToLogin -c src/AppBundle
-> bin/codecept generate:cest acceptance As_An_Admin/IWantToLogin -c src/AppBundle
-> bin/codecept generate:cest acceptance As_Test3_User/IDontWantTologin -c src/AppBundle
-> bin/codecept generate:cest acceptance As_Test1_User/IWantToManageMyOwnProfile -c src/AppBundle
-> bin/codecept generate:cest acceptance As_Test1_User/IDontWantToManageOtherProfiles -c src/AppBundle
-> bin/codecept generate:cest acceptance As_An_Admin/IWantToManageAllUsers -c src/AppBundle
```

We will create a common class in the bootstrap and define all the constants we need for the test.

```
# src/AppBundle/Tests/acceptance/_bootstrap.php

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');
define('TEST1_USERNAME', 'test1');
define('TEST1_PASSWORD', 'test1');
define('TEST2_USERNAME', 'test2');
define('TEST2_PASSWORD', 'test2');
// test3 Account is disabled? See data fixtures to confirm.
define('TEST3_USERNAME', 'test3');
define('TEST3_PASSWORD', 'test3');


class Common
{
	public static function login(AcceptanceTester $I, $user, $pass)
    {
        $I->amOnPage('/login');
        $I->fillField('_username', $user);
        $I->fillField('_password', $pass);
        $I->click('_submit');
    }
}
```

Let us try creating story 10.6

```
# src/AppBundle/Tests/acceptance/As_An_Admin/IWantToManageUsersCest.php

namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToManageUsersCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function login(AcceptanceTester $I)
    {
        Common::login($I, ADMIN_USERNAME, ADMIN_PASSWORD);
    }

   /**
     * Scenario 1.61
     * @before login
     */
    public function listAllProfiles(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/songbird/user/user/list');
        $I->canSeeNumberOfElements('//table[@class="table table-bordered table-striped sonata-ba-list"]/tbody/tr',4);
    }
    ...
```

Noticed that the login class is protected rather than public. Protected class won't be executed when we run the "runtest" command but we can use it as a pre-requisite when testing listAppProfiles scenario for example, ie the @login annotation.

listAllProfiles function goes to the user listing page and check for 4 rows in the table with the "table table-bordered table-striped" classes. The way it is selected is by using [xpath](https://msdn.microsoft.com/en-us/library/ms256086(v=vs.110).aspx). How do I know about the amOnPage and canSeeNumberOfElements functions? Remembered you ran the command "/bin/codecept build" before? This command generates the AcceptanceTester class to be used in the Cest class. All the functions of the AcceptanceTester class can be found in the "src/AppBundle/Tests/_support/_generated/AcceptanceTesterActions.php" class.

You might also notice that I was going to the user listing url directly rather than clicking on the user listing link. *Simulating user clicks should be the way to go because you are simulating user behaviour with acceptance testing**. We do not have the links at the moment. We will change the test once we have the UI updated.

Let us update the runtest script

```
# scripts/runtest

#!/bin/bash

scripts/resetapp
# start selenium server first using the default firefox profile. Feel free to create a new profile.
bin/codecept run acceptance $@ -c src/AppBundle
```

and update the gitignore path

```
# .gitignore
...
src/AppBundle/tests/_output/*
```

Then, run the test only for scenario 10.6.1

```
-> scripts/runtest As_An_Admin/IWantToManageAllUsersCest.php:listAllProfiles
...
OK (1 test, 1 assertion)
```

Looking good, what if the test fails and you want to look at the logs? The log files are all in the "tests/_output/" directory.

Let us write another test for scenario 10.6.2. We will simulate clicking on test3 show button and check the page is loading fine.

```
# src/AppBundle/Tests/acceptance/As_An_Admin/IWantToManageAllUsersCest.php
...
   /**
     * Scenario 10.62
     * @before login
     */
    public function showTest3User(AcceptanceTester $I)
    {
        // go to user listing page
        $I->amOnPage('/admin/app/user/list');
        // cick on show button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[4]/div/a[1]');
        $I->waitForText('test3@songbird.app');
        $I->canSee('test3@songbird.app');
        $I->canSeeInCurrentUrl('/user/4/show');
    }
...
```

Noticed the long xpath selector?

```
(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[4]/div/a[1]
```

This is the xpath for the show button. How do we know where it is located? We can inspect the elements with the developer tool (available in many browser) - see screenshot below:

![developers tools](images/developer_tools.png)

run the test now

```
-> scripts/runtest As_An_Admin/IWantToManageAllUsersCest.php:showTest3User
```

and you should get a success message.

We will now write the test for scenario 10.6.3

```
# src/AppBundle/tests/acceptance/As_An_Admin/IWantToManageAllUsersCest.php
...
    /**
     * Scenario 10.63
     * @before login
     */
    public function editTest3User(AcceptanceTester $I)
    {
        // go to user listing page
        $I->amOnPage('/admin/app/user/list');
        // click on edit button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[4]/div/a[2]');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/app/user/4/edit');
        $I->fillField('//input[@value="test3 Lastname"]', 'lastname3 updated');
        // update
        $I->click('btn_update_and_edit');
        // go back to listing page
        $I->amOnPage('/admin/app/user/list');
        $I->canSee('lastname3 updated');
        // now revert username
        $I->amOnPage('/admin/app/user/4/edit');
        $I->fillField('//input[@value="lastname3 updated"]', 'test3 Lastname');
        $I->click('btn_update_and_edit');
        $I->amOnPage('/admin/app/user/list');
        $I->canSee('test3 Lastname');
    }
...
```

Run the test now to make sure everything is ok before moving on.

```
-> scripts/runtest As_An_Admin/IWantToManageAllUsersCest.php:editTest3User
```

and scenario 10.6.4

```
# src/AppBundle/tests/acceptance/As_An_Admin/IWantToManageAllUsersCest.php
...
   /**
     * Scenario 10.6.4
     * @before login
     */
    public function createAndDeleteNewUser(AcceptanceTester $I)
    {
        // go to create page and fill in form
        $I->amOnPage('/admin/app/user/create');
        $I->fillField('//input[contains(@id, "_username")]', 'test4');
        $I->fillField('//input[contains(@id, "_email")]', 'test4@songbird.app');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', 'test4');
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', 'test4');
        // submit form
        $I->click('btn_create_and_edit');
        // go back to user list
        $I->amOnPage('/admin/app/user/list');
        // i should see new test4 user created
        $I->canSee('test4@songbird.app');

        // now delete user
        // click on edit button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[5]/div/a[3]');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/admin/app/user/5/delete');
        // click on delete button
        $I->click('//button[@type="submit"]');
        // go back to list page
        $I->amOnPage('/admin/app/user/list');
        // I can no longer see test4 user
        $I->cantSee('test4@songbird.app');
    }
...
```

createNewUser test is abit longer. I hope the comments are self explainatory.

Let's run the test just for this scenario

```
-> scripts/runtest As_An_Admin/IWantToManageAllUsersCest.php:createAndDeleteNewUser
```

Feeling confident? We can run all the test together now

```
-> scripts/runtest

Dropped database for connection named `songbird`
Created database `songbird` for connection named default
ATTENTION: This operation should not be executed in a production environment.

Creating database schema...
Database schema created successfully!
  > purging database
  > loading [1] AppBundle\DataFixtures\ORM\LoadUserData
Codeception PHP Testing Framework v2.1.1
Powered by PHPUnit 4.7.7 by Sebastian Bergmann and contributors.

Acceptance Tests (9) -------------------------------------
Try to test (As_An_Admin\IWantToLoginCest::tryToTest)                                                                                                       Ok
List all profiles (As_An_Admin\IWantToManageAllUsersCest::listAllProfiles)                                                                                  Ok
Show test3 user (As_An_Admin\IWantToManageAllUsersCest::showTest3User)                                                                                      Ok
Edit test3 user (As_An_Admin\IWantToManageAllUsersCest::editTest3User)                                                                                      Ok
Create and delete new user (As_An_Admin\IWantToManageAllUsersCest::createAndDeleteNewUser)                                                                  Ok
Try to test (As_Test1_User\IShouldNotBeAbleToManageOtherProfilesCest::tryToTest)                                                                            Ok
Try to test (As_Test1_User\IWantToLoginCest::tryToTest)                                                                                                     Ok
Try to test (As_Test1_User\IWantToManageMyOwnProfileCest::tryToTest)                                                                                        Ok
Try to test (As_Test3_User\IDontWantTologinCest::tryToTest)                                                                                                 Ok
---------------------------------------------------------
Time: 41.19 seconds, Memory: 24.50Mb

OK (9 tests, 13 assertions)
```

Want more detail output? Try this

```
-> ./scripts/runtest --steps
```

How about with debug mode

```
-> ./scripts/runtest -d
```

If you are using mac and got "too many open files" error, you need to change the ulimit to something bigger

```
-> ulimit -n 2048
```

Add this to your ~/.bash_profile if you want to change the limit everytime you open up a shell.

If your machine is slow, sometimes it might take too long before certain text or element is being detected. In that case, use the "waitForxxx" function before the assert statement, like so

```
# wait for element to be loaded first
# you can see all the available functions in src/AppBundle/Tests/_support/_generated/AcceptanceTesterActions.php
$I->waitForElement('//div[contains(@class, "alert-success")]');
# now we can do the assert statement
$I->canSeeElement('//div[contains(@class, "alert-success")]');
```

We have only written the BDD tests for user story 10.6. Are you ready to write acceptance test for the other user stories?

Writing test can be a boring process but essential if you want your software to be robust. A tip to note is that every scenario must have a closure so that it is self-contained. The idea is that you can run a test scenario by itself without affecting the rest of the scenarios. For example, if you change a password in a scenario, you have to remember to change it back so that you can run the next test without worrying that the password has been changed. There are several ways you can achieve this. How can you do it such that it doesn't affect performance?

The workflow in this book is just one of many ways to write BDD tests. At the time of writing, many people uses [behat](http://docs.behat.org/en/v3.0/) as well.

## Summary

In this chapter, we wrote our own CEST class based on different user stories and scenarios. We are now more confident that we have a way to test Songbird's user management functionality as we add more functionalities in the future.

Remember to commit your changes before moving on the next chapter.


Next Chapter: [Chapter 11: Customising the Login Process](https://github.com/bernardpeh/songbird/tree/chapter_11)

Previous Chapter: [Chapter 9: The Admin Panel Part 1](https://github.com/bernardpeh/songbird/tree/chapter_9)

## Stuck? Checkout my code

```
-> git checkout -b chapter_10 origin/chapter_10
-> git clean -fd
```

## Exercises

* Write acceptance test for User Stories 10.1, 10.2, 10.3, 10.4 and 10.5.

* (Optional) Can you think of other business rules for user management? Try adding your own CEST.

## References

* [More BDD Readings](https://en.wikipedia.org/wiki/Behavior-driven_development)

* [User Story](https://en.wikipedia.org/wiki/User_story)

* [integration testing](https://en.wikipedia.org/wiki/Integration_testing)
