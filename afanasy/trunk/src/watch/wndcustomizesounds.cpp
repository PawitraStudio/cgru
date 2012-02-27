#include "wndcustomizesounds.h"

#include <QtGui/QLabel>
#include <QtGui/QLayout>
#include <QtGui/QPushButton>

#include "../libafanasy/environment.h"

#include "../libafqt/qenvironment.h"

#include "colorwidget.h"
#include "filewidget.h"
#include "fontwidget.h"
#include "numberwidget.h"
#include "watch.h"

#define AFOUTPUT
#undef AFOUTPUT
#include "../include/macrooutput.h"

WndCustomizeSounds::WndCustomizeSounds():
   Wnd("Customize Sounds")
{
   QHBoxLayout * hlayout;
   QVBoxLayout * vlayout;
   QLabel * label;

   FileWidget * flw;

   hlayout = new QHBoxLayout( this);

   vlayout = new QVBoxLayout();
#if QT_VERSION >= 0x040300
   vlayout->setContentsMargins( 1, 1, 1, 1);
#endif
   vlayout->setSpacing( 2);
   hlayout->addLayout( vlayout);

   label = new QLabel("Events Sounds:", this);
   label->setAlignment( Qt::AlignHCenter | Qt::AlignVCenter);
   vlayout->addWidget( label);

   flw = new FileWidget( this, &afqt::QEnvironment::soundJobAdded); vlayout->addWidget( flw);
   flw = new FileWidget( this, &afqt::QEnvironment::soundJobDone ); vlayout->addWidget( flw);
   flw = new FileWidget( this, &afqt::QEnvironment::soundJobError); vlayout->addWidget( flw);
}

WndCustomizeSounds::~WndCustomizeSounds()
{
}
