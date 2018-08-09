using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(Quality_Inspection_Crawlers.Startup))]
namespace Quality_Inspection_Crawlers
{
    public partial class Startup
    {
        public void Configuration(IAppBuilder app)
        {
            ConfigureAuth(app);
        }
    }
}
