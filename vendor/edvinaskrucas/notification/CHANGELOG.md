#4.0.0
---

* Support for Laravel 5

---

# 2.0.1
---

* Fix ```$this``` usage when in Closure in ServiceProvider

---

# 2.0.0
---

* Add message types dynamically
* Changes in position and alias API
* Added events
* Updated ```config.php``` file
* Messages now flashed using events
* Refactored library

---

# 1.2.3
---

* Check if ```session.store``` is set before using it.

---

# 1.2

---

* Refactored how messages are stored in bag.
* Added method ```getAtPosition($position)``` to a NotificationBag.
* Added method ```getAliased($alias)``` to a NotificationBag and Collection classes.
* Added method ```group()``` to NotificationBag to allow render grouping.
* When working directly with ```Notification```, you will work just with default container.
* Session prefix now is configurable.
* Refactored ```Notification``` class, now uses ```__call()``` to call methods on a default container.

---

# 1.1.1

---

* Added test to test message flashing after adding alias and / or position.
* Fix message flashing when using ```alias()``` and / or ```atPosition```.

---

# 1.1

---

* Added methods to clear notifications for a given type / all in a container.
* Message aliasing, allows to use alias on message, so it can be overridden when needed.
* Message positioning.

---

# 1.0.1

---

* Fixed $app scopes when registering component.

---

# 1.0

---

* Initial release.