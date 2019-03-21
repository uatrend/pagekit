JSONStorage
==========

A simple storage helper inspired by the redis api.

# Usage

    var db = JSONStorage.select("mydb");

    db.set("mykey", 1);
    
    db.incr("mykey");
    
    db.get("mykey"); // 2

    // Lists

    db.rpush("mylist", "item1");
    db.rpush("mylist", "item2");
    db.lpush("mylist", "item3");

    db.get("mylist");  // ["item3", "item1", "item2"]

    // hashes

    db.hset("myhash", "myfield", 1);
    db.hmset("myhash", "myfield-1", "value-1", "myfield-2", "value-2");

    db.hkeys("myhash"); // ["myfield", "myfield-1", "myfield-2"]
    db.hvals("myhash"); // [1, "value-1", "value-2"]

    db.hget("myhash", "myfield"); // 1

    db.get("myhash"); // {"myfield":1, "myfield-1":"value-1", "myfield-2":"value-2"}

## implemented methods

    set, setex, get, exists, del, type, append, incr, decr, 
    llen, lpush, rpush, lset, lindex,
    hset, hget, hgetall, hexists, hkeys, hvals, hlen, hincrby, hmset, hmget

## Adaptars

- memory (default)
- local   - using window.localStorage
- session - using window.sessionStorage

```
var memory  = JSONStorage.select("mydb"),
    local   = JSONStorage.select("mydb", "local"),
    session = JSONStorage.select("mydb", "session");
```
