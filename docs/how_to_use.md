# How to Use

In general, after installing **`codeigniter-dea-rule`**, a new rule is added to Codegniter's validation rules, named `is_temp_email`. From now on, you can use it like other CI4 rules.

`is_temp_email` rule could now be used just like any other rule:

| Rule           | Parameter | Description                                                | Example       |
|----------------|-----------| -----------------------------------------------------------| --------------|
| `is_temp_email`| No        | Fails if the field contains a Disposable Temporary E-mail. |`is_temp_email`|

## How To Use Rule




```php
// e.g.
$validation->setRules([
    'email' => 'required|max_length[19]|is_temp_email',
]);
```

```php
// e.g.
// In Controller.

if (! $this->validate([
    'email' => 'required|max_length[19]|is_temp_email',
    'password' => 'required|min_length[10]',
])) {
    // The validation failed.
    return view('login', [
        'errors' => $this->validator->getErrors(),
    ]);
}

// The validation was successful.
```

```php
// ...

$rules = [
    'email'    => 'required|max_length[254]|valid_email|is_temp_email',
    'password' => 'required|max_length[255]|min_length[10]',
    'passconf' => 'required|max_length[255]|matches[password]',
];

// ...
```

## Practical Examples For CodeIgniter Shield

As you know, [CodeIgniter Shield](https://github.com/codeigniter4/shield) allows you to easily apply your [custom validation rules](https://codeigniter4.github.io/shield/customization/validation_rules) to fields. In the example below, we have shown how to use `is_temp_email` rule for the user registration form.
Add the $registration property with the all validation rules and `is_temp_email` for registration in **app/Config/Validation.php**:
```php
//--------------------------------------------------------------------
// Rules For Registration
//--------------------------------------------------------------------
public $registration = [
    'username' => [
        'label' => 'Auth.username',
        'rules' => [
            'required',
            'max_length[30]',
            'min_length[3]',
            'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            'is_unique[users.username]',
        ],
    ],
    'email' => [
        'label' => 'Auth.email',
        'rules' => [
            'required',
            'max_length[254]',
            'valid_email',
            'is_unique[auth_identities.secret]',
            'is_temp_email', // just add this line
        ],
    ],
    'password' => [
        'label' => 'Auth.password',
        'rules' => 'required|max_byte[72]|strong_password[]',
        'errors' => [
            'max_byte' => 'Auth.errorPasswordTooLongBytes'
        ]
    ],
    'password_confirm' => [
        'label' => 'Auth.passwordConfirm',
        'rules' => 'required|matches[password]',
    ],
];
```
