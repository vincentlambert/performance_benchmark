# How use it

dans un fichier composer.json
```
{
 "repositories": {
    "vincentlambert.performance_benchmark": {
        "type": "package",
        "package": {
            "name": "vincentlambert/performance_benchmark",
            "version": "master",
            "type": "drupal-module",
            "source": {
                "url": "https://github.com/vincentlambert/performance_benchmark.git",
                "type": "git",
                "reference": "master"
            }
        }
    }
 }
}
```

Puis ```composer require vincentlambert/performance_benchmark```
