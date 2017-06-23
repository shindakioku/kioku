#include "make.h"

const string common_path = "app/";
const string file_type = ".php";
const string controllers = "Controllers/";
const string entities = "Entities/";
const string middleware = "Middleware/";
const string views_path = "resources/views";

bool
Make::get_error()
{
    if (0 == this->error)
        return false;

    return true;
}

void
Make::make_controller(string name)
{
    string code;
    string *after_create_directory = new string[3];
    after_create_directory = this->delegate_for_make(name, controllers);

    code = {
        "<?php \n \n"
            "namespace App\\Controllers" + after_create_directory[0] + ";"
            "\n \n"
            "class " + after_create_directory[1] + "\n{"
            "\n}"
    };

    if (0 == this->error)
        this->create_file(common_path + controllers + '/' + name + file_type, code);
    else
        cout << "Me sorry, try later" << endl;
}

void
Make::make_middleware(string name)
{
    string code;
    string *after_create_directory = new string[3];
    after_create_directory = this->delegate_for_make(name, middleware);

    code = {
        "<?php \n \n"
            "namespace App\\Middleware" + after_create_directory[0] + ";"
            "\n \n"
            "class " + after_create_directory[1] + "\n{\n"
            "    public function handle($request, callable $next)\n"
            "    {\n"
            "         // Your code her"
            "\n"
            "    }\n"
            "}"
    };

    if (0 == this->error)
        this->create_file(common_path + middleware + '/' + name + file_type, code);
    else
        cout << "Me sorry, try later" << endl;
}

void
Make::make_entity(string name, string table_name)
{
    string code;
    string *after_create_directory = new string[3];
    after_create_directory = this->delegate_for_make(name, entities);

    code = {
        "<?php \n \n"
            "namespace App\\Entities" + after_create_directory[0] + ";\n"
            "\nuse Doctrine\\ORM\\Mapping as ORM;\n"
            "\n/**"
            "\n * Class " + after_create_directory[1] + "\n"
            " * @ORM\\Entity"
            "\n * @ORM\\table(name="" + table_name + "")\n"
            " */"
            "\nclass " + after_create_directory[1] + "\n"
            "{ \n"
            "    /** \n"
            "     * @var integer $id\n"
            "     * \n"
            "     * @ORM\\Column(name=""id"", type=""bigint"")\n"
            "     * @ORM\\Id\n"
            "     * @ORM\\GeneratedValue(strategy=""IDENTITY"")\n"
            "     */ \n"
            "     private $id;"
            "\n}"
    };

    if (0 == this->error)
        this->create_file(common_path + entities + '/' + name + file_type, code);
    else
        cout << "Me sorry, try later" << endl;
}

void
Make::make_resource(string route, string controller_name, char views, string type, string entity, string file_route_name)
{
    string code;
    string *after_create_directory = new string[3];
    after_create_directory = this->delegate_for_make(controller_name, controllers);

    ofstream file("routes/" + file_route_name, ios_base::app);
    file << "\n\n$route->resource('" + route + "', 'App\\Controllers" + after_create_directory[0] + "\\"
        + after_create_directory[1] + "');";
    file.close();

    if ('y' == views)
    {
        string nameForView = route.erase(0, 1);
        code = {
            "<?php\n"
                "\nnamespace App\\Controllers" + after_create_directory[0] + ";\n"
                "\nuse Kioku\\Http\\Request;"
                "\nuse Kioku\\Doctrine;"
                "\nuse App\\Entities\\" + entity + ";\n"
                "\nclass " + after_create_directory[1] + "\n{\n"
                "    protected $em;\n\n"
                "    public function __construct()\n"
                "    {\n"
                "        $this->em = app()->make(Doctrine::class)->em();\n"
                "    }\n\n"
                "    public function index()\n"
                "    {\n"
                "        return view('" + nameForView + "@index" + type + "');\n"
                "    }\n\n"
                "    public function create()\n"
                "    {\n"
                "        return view('" + nameForView + "@create" + type + "');\n"
                "    }\n\n"
                "    public function store(Request $request)\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function show(int $id)\n"
                "    {\n"
                "        $" + nameForView + " = $this->em->find('App\\Entities\\" + entity + "', $id);\n\n"
                "        return view('" + nameForView + "@show" + type + "', ['" + nameForView + "' => $"
                + nameForView
                + "]);\n"
                    "    }\n\n"
                    "    public function edit(int $id)\n"
                    "    {\n"
                    "        $" + nameForView + " = $this->em->find('App\\Entities\\" + entity + "', $id);\n\n"
                "        return view('" + nameForView + "@edit" + type + "', ['" + nameForView + "' => $"
                + nameForView
                + "]);\n"
                    "    }\n\n"
                    "    public function update(Request $request, int $id)\n"
                    "    {\n"
                    "        // Your code here\n"
                    "    }\n\n"
                    "    public function delete(int $id)\n"
                    "    {\n"
                    "        // Your code here\n"
                    "    }"
                    "\n}"
        };
    }
    else
    {
        code = {
            "<?php\n"
                "\nnamespace App\\Controllers" + after_create_directory[0] + ";\n"
                "\nuse Kioku\\Http\\Request;\n"
                "\nclass " + after_create_directory[1] + "\n{\n"
                "    public function index()\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function create()\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function store(Request $request)\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function show(int $id)\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function edit(int $id)\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function update(Request $request, int $id)\n"
                "    {\n"
                "        // Your code here\n"
                "    }\n\n"
                "    public function delete(int $id)\n"
                "    {\n"
                "        // Your code here\n"
                "    }"
                "\n}"
        };
    }

    this->create_file(common_path + controllers + '/' + controller_name + file_type, code);
}

void
Make::generate_views_for_resource(string url, string type)
{
    int views = 4;

    string names[views] = {
        "index", "create", "show", "edit"
    };

    mkdir((views_path + url).c_str(), S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH);

    for (int i = 0; i < views; i++)
    {
        this->create_file(views_path + url + '/' + names[i] + type, "");
    }
}

string *
Make::create_directory(string name, string const path)
{
    vector <std::string> v = explode(name, '/');
    string dirName;
    string *result = new string[3];
    result[1] = v.back();

    dirName += common_path;
    dirName += path;

    for (int i = 0; i < (v.size() - 1); i++)
    {
        dirName += v[i] + '/';
        result[0] += "\\" + v[i];

        if (0 == this->error)
            this->error = mkdir(dirName.c_str(), S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH);
    }

    return result;
}

bool
Make::create_file(string name, string put_in_file)
{
    ofstream file(name);
    file << put_in_file;
    file.close();

    return true;
}

vector <std::string>
Make::explode(std::string const &s, char delim)
{
    vector <std::string> result;
    istringstream iss(s);

    for (std::string token; std::getline(iss, token, delim);)
        result.push_back(std::move(token));

    return result;
}

string *
Make::delegate_for_make(string name, string path)
{
    string *result = new string[3];

    if (name.find('/') != string::npos)
        result = this->create_directory(name, path);
    else
    {
        result[0] = "";
        result[1] = name;
    }

    return result;
}