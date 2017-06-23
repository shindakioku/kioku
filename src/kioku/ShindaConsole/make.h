#include <iostream>
#include <string>
#include <fstream>
#include <sstream>
#include <sys/types.h>
#include <sys/stat.h>
#include <vector>

using namespace std;

class Make
{
public:
    bool
    get_error();

    void
    make_controller(string name);

    void
    make_middleware(string name);

    void
    make_entity(string name, string table_name);

    void
    make_resource(string route, string controller_name, char views, string type, string entity, string file_route_name);

    void
    generate_views_for_resource(string url, string type);

private:
    int error = 0;

    string *
    create_directory(string name, string const path);

    bool
    create_file(string name, string put_in_file);

    vector <std::string>
    explode(std::string const &s, char delim);

    string *
    delegate_for_make(string name, string path);
};
