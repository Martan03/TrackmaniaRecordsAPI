# TrackmaniaRecordsAPI

I'm competitive type of person, so I decided to make [Trackmania Records website](https://github.com/Martan03/TrackmaniaRecords) so me and my friends 
can write down the records and then compete with each other. One of my friends wanted to make custom website for this, so that's why I created this API.

# API Calls:
## Records:
```/record```: returns all records from the database  
### Get
```/record/get?record_id=<id>```: returns record with given ID  
```record/get?season_id=<id>```: returns best times from each level in given season  
```record/get?season_id=<id>&level=<level>```: returns all times in given level of the given season  
### Manage
```/record/manage?record_holder=<name>&record_time=<time>&record_season=<id>&record_level=<level>```: creates new record  
```/record/manage?record_id=<id>```: edits record with given ID, add parameters you want to edit (record_holder=Tester)  
#### Remove
```/record/remove?record_id=<id>```: removes record with given ID
