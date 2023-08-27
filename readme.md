# Employees app - Nette

## How to setup

### With Docker (using prepared script)

1. Ensure you have file **database.xml** in **storage/app** directory
2. Run ```sudo chmod -R 755 ./scripts```
3. Run ```./scripts/start.sh```

### With Docker (using commands)

1. Ensure you have file **database.xml** in **storage/app** directory
2. Run ```docker-compose up -d```
3. Run ```docker-compose exec app npm run build``` to build Vite assets

## Description of used approaches and technologies

### PHP

- Created **XMLDatabase** class used to abstract communication between repositories and XML datastore.
- Created **models** used to encapsulate row attributes and provide additional functionality (can and should be
  improved/extended)
    - Implemented modifiable columns list (possible future issue: possible mismatch of unsaved rows, currently
      non-breaking, possible fix: migration system)
    - Implemented explicit attribute casting
- Created **repositories** to provide simplified interface for data (models) retrieval
    - Implemented primitive sorting (for now only for numeric values)
    - Implemented primitive grouping (for now only for age)
- Created **form factory** to handle Employee edit and create form creation
    - Implemented validation rules
- Created basic tests to verify valid XML datastore configuration

### CSS

- BEM methodology
- Used **Vite** (example setup credits: in separated files)
- Used **SCSS** preprocessor
    - Themeable and separated (development-wise) setup
- Used **Font Awesome 6** icons

### JS

- Used **Chart.js**
- Code in inline script tag (very few lines of code, to be extracted when JS codebase gets bigger)

## Could be implemented

- Pagination
- Sorting
- Filtering
- Model attribute decasting, custom casts

and more...

## How to add custom attribute to models

1. Add column name to ```$columns``` attribute in your model class, ex. **Employee.php**
2. [OPTIONAL] Define cast in ```$casts``` attribute
3. Add column to table in corresponding **default.latte** view
4. Add field, rules to form factory used by edit and create views
5. Add suitable input type in edit and create views

NOTE 1: It could be possibly restructured to omit changes in views (steps 3 and 5; adding inputs in view) making it simpler, but in
this case it would be a bit
over-engineering and simpler implementation would have visual/technical drawbacks.

NOTE 2: Edit and create form content (in view) could be merged into single to provide simpler modification, but in this case I
prefer readability and ability to control content separately.

NOTE 3: Models now supports only table-like (2D) data, arrays can be stored flattened.
