
# Coin Sell From Wallet

By Erfan Ebrahimi

## Run Locally

- Need php version 8.1 or upper

- Download project form Git

```bash
  git clone https://github.com/yeganehha/sellcoin sellcoin
  cd sellcoin
  composer install 
```
- Then open `.env` file and config database and setting of coins cache
- Also, you can update setting from `config/setting.php`

- Then
```bash
  php artisan migrate --seed
  php artisan serve
```

- Then open [http://127.0.0.1:8000](http://127.0.0.1:8000)

- In other terminal use bellow command: `[ Imporant ]`
```bash
  PHP artisan queue:listen
```

## FAQ

#### 1. How To Add New Exchange Platform?

For adding new platform driver just need to call:

```
    PHP artisan make:platform <name>
```

#### 2. How get coins in Binance?
Use [coingecko.com](https://www.coingecko.com/api/)


#### 3. How get coins in CoinEx?
Use `MockHandler` to filter coins and show some [coingecko.com](https://www.coingecko.com/api/) As a result; the sent list will be different for Binance and CoinEx.



#### 4. Why is a separate table not made for order coins?
Because a mistake might happen and imagine that the list of coins is not received from the api and is read from the database. I decided to put the purchased coin in the order table even though it was wrong.

## Running Tests

To run tests, run the following command

```bash
  php artisan test
```


## Git Flow Diagram

![Git Flow](https://erfanebrahimi.ir/temp.png)


