# Purge on Save

Send a HTTP request with PURGE method on every post save (creation or update) to clean a service cache.

Useful if there is a web server in front of WordPress caching the responses. Please note that the web server must be properly configured to accept this type of request.

Works with multiple services at the same time.

## Installation
1. Download the plugin into your plugins directory.
2. Enable in the WordPress admin dashboard.

## Configuration
The following must be added as environment variables:

- The origin of the service with `[SERVICE]_ORIGIN`.
    - Example: `API_ORIGIN` to `https://api.acme.org`.
- Configure with `PURGE_[SERVICE]_CACHE_ON_SAVE` to a boolean, like:
    - Example: `PURGE_API_CACHE_ON_SAVE` to `true`.