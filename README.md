![Two-Factor Authentication](https://raw.githubusercontent.com/born05/craft-twofactorauthentication/craft-3.1/plugin-icon.png)

# Two-Factor Authentication

Craft 4 plugin for two-factor or two-step login using Time Based OTP (TOTP, like Google Authenticator).
Every user can setup TOTP themselves, the plugin does not force users. Admins can list usage in user tables.

## Inner working

Login works as usual for users without 2-factor auth.

When enabled, the user is redirected to the 2-factor verification page after login.
This means the user is already logged in. When the user tries to visit an other Control Panel page than the public ones before verification, the logout is triggered. This blocks the user from visiting the CP unverified.

## Requirements

- Craft 4.0.0 and up
- PHP 8.0.2 and up

## Setting up back end 2FA

- Set `verifyBackEnd` to `true` in the config file (this is the default).
- Set `forceBackEnd` to `true` if you want to prevent users from accessing the control panel without first enabling 2FA.

## Setting up front end 2FA

When using a login for front end users, the following steps add 2FA support.

- Copy the [two-factor-authentication.php](https://github.com/born05/craft-twofactorauthentication/blob/craft-3.1/examples/two-factor-authentication.php) file to your `config/` folder.
- Set `verifyFrontEnd` to `true` in the config file.
- Define what urls should be protected with 2FA verification. Choose between using the `frontEndPathAllow` or `frontEndPathExclude`! Using both will block everything! See config for additional info.
- Build a 2FA login-verify form accessible by url like the [example twig](https://github.com/born05/craft-twofactorauthentication/blob/craft-3.1/examples/login-verify.twig).
- Set the `verifyPath`. For our `login-verify.twig` example the path would be `login-verify`.
- Allow users setting up 2FA in front end by building a template like the [example twig](https://github.com/born05/craft-twofactorauthentication/blob/craft-3.1/examples/two-factor-settings.twig).
- Set the `settingsPath`. For our `two-factor-settings.twig` example the path would be `two-factor-settings`.

## Setting up config

Copy the [two-factor-authentication.php](https://github.com/born05/craft-twofactorauthentication/blob/craft-3.1/examples/two-factor-authentication.php) file to your `config/` folder.

## Resetting a user's 2FA

Simply remove the user's `twofactorauthentication_user` record. This disables 2FA for that user.

## Screens

#### Setting screen when turning 2FA on
![Setting screen when turning 2FA on](https://raw.githubusercontent.com/born05/craft-twofactorauthentication/craft-3.1/settings-turn-on.png)

#### Setting screen when turning 2FA off
![Setting screen when turning 2FA off](https://raw.githubusercontent.com/born05/craft-twofactorauthentication/craft-3.1/settings-turn-off.png)

#### Login verification screen
![Login verification screen](https://raw.githubusercontent.com/born05/craft-twofactorauthentication/craft-3.1/login-verification.png)

## License

Copyright © [Born05](https://www.born05.com/)

See [license](https://github.com/born05/craft-twofactorauthentication/blob/craft-3.1/LICENSE.md)
