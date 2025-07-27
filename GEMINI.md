## General code instructions
 
- Don't generate code comments above the methods or code blocks if they are obvious. Generate comments only for something that needs extra explanation for the reasons why that code was written
- When changing the code, don't comment it out, unless specifically instructed. Assume the old code will stay in Git history.
 
---
 
## Laravel/PHP instructions
 
- For DB pivot tables, use correct alphabetical order, like "project_role" instead of "role_project"
- I am using Laravel Herd locally, so always assume that the main URL of the project is `http://[folder_name].test`
- **Eloquent Observers** should be registered in Eloquent Models with PHP Attributes, and not in AppServiceProvider. Example: `#[ObservedBy([UserObserver::class])]` with `use Illuminate\Database\Eloquent\Attributes\ObservedBy;` on top
- When generating Controllers, put validation in Form Request classes
- Aim for "slim" Controllers and put larger logic pieces in Service classes
- Use Laravel helpers instead of `use` section classes whenever possible. Examples: use `auth()->id()` instead of `Auth::id()` and adding `Auth` in the `use` section. Another example: use `redirect()->route()` instead of `Redirect::route()`.
- In PHP, use `match` operator over `switch` whenever possible
 
---
 
## Use Laravel 11+ skeleton structure
 
- **Service Providers**: there are no other service providers except AppServiceProvider. Don't create new service providers unless absolutely necessary. Use Laravel 11+ new features, instead. Or, if you really need to create a new service provider, register it in `bootstrap/providers.php` and not `config/app.php` like it used to be before Laravel 11.
- **Event Listeners**: since Laravel 11, Listeners auto-listen for the events if they are type-hinted correctly.
- **Console Scheduler**: scheduled commands should be in `routes/console.php` and not `app/Console/Kernel.php` which doesn't exist since Laravel 11.
- **Middleware**: whenever possible, use Middleware by class name in the routes. But if you do need to register Middleware alias, it should be registered in `bootstrap/app.php` and not `app/Http/Kernel.php` which doesn't exist since Laravel 11.
- **Tailwind**: in new Blade pages, use Tailwind and not Bootstrap, unless instructed otherwise in the prompt. Tailwind is already pre-configured since Laravel 11, with Vite.
- **Faker**: in Factories, use `fake()` helper instead of `$this->faker`.
- **Policies**: Laravel automatically auto-discovers Policies, no need to register them in the Service Providers.
 
---
 
## Using PHP Services in Controllers
 
- If Service class is used only in ONE method of Controller, inject it directly into that method with type-hinting.
- If Service class is used in MULTIPLE methods of Controller, initialize it in Constructor.
- Use PHP 8 constructor property promotion. Don't create empty Constructor method if it doesn't have any parameters.
 
---
 
## Filament Rules
 
- When using Filament 4, generate resources `php artisan make:filament-resource` using `--view` flag, to avoid extra question in the Terminal.