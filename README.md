# TrackmaniaRecordsAPI

I'm competitive type of person so I decided to make [Trackmania Records website](https://github.com/Martan03/TrackmaniaRecords) so me and my friends 
could set records and then compete with each other. One of my friends wanted to make custom website for it, so I made this API.

Also, I did all the calls using GET (my friend ask me to do it like this)

# API Calls:
## Records:
```/record```: returns all records from the database  
### Get
```/record/get?record_id=<id>```: returns record with given ID  
```record/get?season_id=<id>```: returns best times from each level in given season  
```record/get?season_id=<id>&level=<level>```: returns all times in given level of the given season  
### Manage
If URL contains ```record_id```, than it edits existing record  
```/record/manage?record_holder=<name>&record_time=<time>&record_season=<id>&record_level=<level>```: creates new record  
```/record/manage?record_id=<id>&record_holder=<name>...```: edits record with given ID, all parameters must be included
### Remove
```/record/remove?record_id=<id>```: removes record with given ID

## Seasons:
```/season```: returns all season from the database  
### Get
```/season/get?season_id=<id>```: returns season with given ID  
```/season/get?season_year=<year>&season_name=<name>```: returns season with given Year and Name  
### Manage
If URL contains ```season_id```, than it edits existing season  
```/season/manage?season_year=<year>&season_name=<name>```: creates new season  
```/season/manage?season_id=<id>&season_name=<name>...```: edits season with given ID, all parameters must be included  
### Remove
```/season/remove?season_id=<id>```: remove season with given ID  

## Links
- **Author:** [Martan03](https://github.com/Martan03)
- **GitHub repository:** [TrackmaniaRecordsAPI](https://github.com/Martan03/TrackmaniaRecordsAPI)
- **Author website:** [martan03.github.io/Portfolio](https://martan03.github.io/Portfolio/)
